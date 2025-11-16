<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coversDir = public_path('covers');

        // Ensure public/covers exists
        if (! File::isDirectory($coversDir)) {
            File::makeDirectory($coversDir, 0755, true);
        }

        // Placeholder image path used when a specific cover file isn't available.
        // You can place a default image at database/seeders/placeholders/default-book.jpg
        $placeholder = database_path('seeders/placeholders/default-book.jpg');

        // Sample books data (you can add more). Each has optional 'cover' filename and optional 'author_name'.
        $sampleBooks = [
            [
                'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                'isbn'  => '9780132350884',
                'author_name' => 'Robert C. Martin', // will try to attach by name
                'cover' => 'clean-code.jpg',
                'quantity' => 3,
            ],
            [
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'isbn'  => '9780201485677',
                'author_name' => 'Martin Fowler',
                'cover' => 'refactoring.jpg',
                'quantity' => 2,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'isbn'  => '9780201616224',
                'author_name' => 'Andrew Hunt',
                'cover' => 'pragmatic-programmer.jpg',
                'quantity' => 4,
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'isbn'  => '9780201633610',
                'author_name' => 'Erich Gamma',
                'cover' => 'design-patterns.jpg',
                'quantity' => 2,
            ],
            [
                'title' => 'Eloquent JavaScript',
                'isbn'  => '9781593279509',
                'author_name' => 'Marijn Haverbeke',
                'cover' => 'eloquent-javascript.jpg',
                'quantity' => 2,
            ],
            [
                'title' => 'You Don\'t Know JS (book series)',
                'isbn'  => '9781491904244',
                'author_name' => 'Kyle Simpson',
                'cover' => 'ydkjs.jpg',
                'quantity' => 3,
            ],
            [
                'title' => 'Introduction to Algorithms',
                'isbn'  => '9780262033848',
                'author_name' => 'Thomas H. Cormen',
                'cover' => 'intro-algorithms.jpg',
                'quantity' => 1,
            ],
        ];

        foreach ($sampleBooks as $b) {
            try {
                // Try to find author by name (seeded previously). If not found, use null.
                $authorId = null;
                if (!empty($b['author_name']) && class_exists(Author::class)) {
                    $found = Author::where('name', 'like', $b['author_name'])->first();
                    // also try exact or partial match if simple search fails
                    if (!$found) {
                        $found = Author::where('name', 'like', '%' . $b['author_name'] . '%')->first();
                    }
                    $authorId = $found?->id;
                }

                // Create or update the book by ISBN if provided, otherwise by title
                $match = !empty($b['isbn']) ? ['isbn' => $b['isbn']] : ['title' => $b['title']];

                $book = Book::updateOrCreate(
                    $match,
                    [
                        'title' => $b['title'],
                        'isbn'  => $b['isbn'] ?? null,
                        'author_id' => $authorId,
                        'quantity' => $b['quantity'] ?? 1,
                    ]
                );

                // Handle cover image file: if cover filename provided, ensure file exists in public/covers,
                // else copy placeholder image (or create an empty file) so UI has something to point to.
                $coverFile = $b['cover'] ?? null;
                if ($coverFile) {
                    $dest = $coversDir . '/' . $coverFile;
                    if (! File::exists($dest)) {
                        // Prefer copying a file from database placeholders if present, else try public/covers sample (if you included some),
                        // otherwise create an empty file to avoid 404s.
                        $copied = false;
                        // If there is a placeholder image in database/seeders/placeholders
                        if (File::exists($placeholder)) {
                            try {
                                File::copy($placeholder, $dest);
                                $copied = true;
                            } catch (\Throwable $e) {
                                // ignore
                            }
                        }

                        // If not copied and there is a cover in public/covers already (maybe you added manually), skip
                        if (! $copied) {
                            // create an empty placeholder file to avoid missing file errors in views
                            try {
                                File::put($dest, '');
                            } catch (\Throwable $e) {
                                // ignore write errors
                            }
                        }
                    }

                    // Save cover filename in DB if column exists and Book model supports it
                    if (in_array('cover_path', (new Book())->getFillable()) || in_array('cover', (new Book())->getFillable())) {
                        // prefer fillable name 'cover_path' or 'cover'
                        if (in_array('cover_path', (new Book())->getFillable())) {
                            $book->cover_path = 'covers/' . $coverFile;
                        } else {
                            $book->cover = 'covers/' . $coverFile;
                        }
                        $book->save();
                    }
                }

                $this->command->info("Seeded/updated book: {$book->title}");
            } catch (\Throwable $e) {
                // Log and continue - don't let a single book fail the seeder
                Log::warning("BookSeeder failed for title {$b['title']}: " . $e->getMessage());
                $this->command->warn("Failed to seed book: {$b['title']}");
            }
        }

        $this->command->info('✅ BookSeeder completed successfully.');
    }
}
