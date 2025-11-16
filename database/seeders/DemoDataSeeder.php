<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Author;
use App\Models\Book;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting DemoDataSeeder...');

        // Directories used for images
        $coversDir  = public_path('covers');
        $authorsDir = public_path('images/authors');

        // Create directories if missing
        foreach ([$coversDir, $authorsDir] as $d) {
            if (! File::isDirectory($d)) {
                try {
                    File::makeDirectory($d, 0755, true);
                    $this->command->line("Created directory: {$d}");
                } catch (\Throwable $e) {
                    $this->command->warn("Could not create {$d}: " . $e->getMessage());
                }
            }
        }

        // Placeholder image path (optional) - used if specific image missing
        $placeholder = database_path('seeders/placeholders/default-book.jpg');
        if (! File::exists($placeholder)) {
            // fallback to author placeholder if book placeholder missing
            $placeholder = database_path('seeders/placeholders/default-author.jpg');
        }

        // Create sample authors (updateOrCreate so it's safe to run multiple times)
        $authorsData = [
            [
                'name' => 'Robert C. Martin',
                'bio' => 'Uncle Bob — author and software craftsmanship advocate.',
                'website' => 'https://cleancoders.com/robert-martin',
                'cover' => 'robert-martin.jpg',
            ],
            [
                'name' => 'Martin Fowler',
                'bio' => 'Software engineer, author, and speaker focused on refactoring & design.',
                'website' => 'https://martinfowler.com/',
                'cover' => 'martin-fowler.jpg',
            ],
            [
                'name' => 'Andrew Hunt',
                'bio' => 'Co-author of The Pragmatic Programmer and mentor to many developers.',
                'website' => 'https://pragprog.com/',
                'cover' => 'andrew-hunt.jpg',
            ],
            [
                'name' => 'David Thomas',
                'bio' => 'Co-author of The Pragmatic Programmer and long-time software practitioner.',
                'website' => 'https://pragprog.com/',
                'cover' => 'david-thomas.jpg',
            ],
        ];

        $authors = [];
        foreach ($authorsData as $a) {
            try {
                $author = Author::updateOrCreate(
                    ['name' => $a['name']],
                    [
                        'bio' => $a['bio'] ?? null,
                        'website' => $a['website'] ?? null,
                        'cover' => $a['cover'] ?? null,
                    ]
                );

                // ensure cover file exists in public/images/authors
                $dest = $authorsDir . '/' . ($a['cover'] ?? 'author-placeholder.jpg');
                if (! File::exists($dest)) {
                    try {
                        if (File::exists(database_path('seeders/placeholders/default-author.jpg'))) {
                            File::copy(database_path('seeders/placeholders/default-author.jpg'), $dest);
                        } else {
                            // create an empty placeholder file to avoid 404s
                            File::put($dest, '');
                        }
                    } catch (\Throwable $e) {
                        Log::warning("Failed to prepare cover for author {$a['name']}: " . $e->getMessage());
                    }
                }

                $authors[$a['name']] = $author;
                $this->command->info("Seeded/updated author: {$author->name}");
            } catch (\Throwable $e) {
                Log::warning("DemoDataSeeder: failed to create author {$a['name']}: " . $e->getMessage());
                $this->command->warn("Failed to seed author: {$a['name']}");
            }
        }

        // Sample books that reference the authors above.
        $booksData = [
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'isbn' => '9780132350884',
                'author_name' => 'Robert C. Martin',
                'quantity' => 3,
                'cover' => 'clean-code.jpg',
                'description' => 'Guidelines and best practices for writing clean, maintainable code.',
            ],
            [
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'isbn' => '9780201485677',
                'author_name' => 'Martin Fowler',
                'quantity' => 2,
                'cover' => 'refactoring.jpg',
                'description' => 'A practical guide to improving legacy code safely.',
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'isbn' => '9780201616224',
                'author_name' => 'Andrew Hunt',
                'quantity' => 4,
                'cover' => 'pragmatic-programmer.jpg',
                'description' => 'Timeless advice for professional software development.',
            ],
            [
                'title' => 'The Pragmatic Programmer (co-author)',
                'isbn' => '9780201616224-2',
                'author_name' => 'David Thomas',
                'quantity' => 1,
                'cover' => 'pragmatic-programmer-2.jpg',
                'description' => 'Alternate edition entry for demo purposes.',
            ],
        ];

        foreach ($booksData as $b) {
            try {
                // find author id if available
                $authorId = null;
                if (!empty($b['author_name']) && isset($authors[$b['author_name']])) {
                    $authorId = $authors[$b['author_name']]->id;
                } else {
                    // fallback: try to find author in DB
                    $found = Author::where('name', 'like', $b['author_name'] ?? '')->first();
                    $authorId = $found?->id;
                }

                $match = !empty($b['isbn']) ? ['isbn' => $b['isbn']] : ['title' => $b['title']];

                $book = Book::updateOrCreate(
                    $match,
                    [
                        'title' => $b['title'],
                        'isbn'  => $b['isbn'] ?? null,
                        'author_id' => $authorId,
                        'quantity' => $b['quantity'] ?? 1,
                        'cover_path' => !empty($b['cover']) ? 'covers/' . $b['cover'] : null,
                    ]
                );

                // ensure book cover exists in public/covers (use placeholder if necessary)
                if (!empty($b['cover'])) {
                    $destCover = $coversDir . '/' . $b['cover'];
                    if (! File::exists($destCover)) {
                        try {
                            if (File::exists(database_path('seeders/placeholders/default-book.jpg'))) {
                                File::copy(database_path('seeders/placeholders/default-book.jpg'), $destCover);
                            } else {
                                // create empty file so view won't 404
                                File::put($destCover, '');
                            }
                        } catch (\Throwable $e) {
                            Log::warning("DemoDataSeeder: failed to copy cover for book {$b['title']}: " . $e->getMessage());
                        }
                    }
                }

                $this->command->info("Seeded/updated book: {$book->title}");
            } catch (\Throwable $e) {
                Log::warning("DemoDataSeeder: failed to create book {$b['title']}: " . $e->getMessage());
                $this->command->warn("Failed to seed book: {$b['title']}");
            }
        }

        $this->command->info('DemoDataSeeder completed.');
    }
}
