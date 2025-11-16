<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Directory for author cover images
        $imageDir = public_path('images/authors');

        // Create directory if not exists
        if (!File::isDirectory($imageDir)) {
            File::makeDirectory($imageDir, 0755, true);
        }

        // Sample authors — realistic names and sample data
        $authors = [
            [
                'name' => 'Harper Collins',
                'bio' => 'A celebrated author known for modern fiction and vivid storytelling. Harper has written over 10 award-winning novels.',
                'website' => 'https://example.com/harper-collins',
                'cover' => 'harper-collins.jpg',
            ],
            [
                'name' => 'Samuel Green',
                'bio' => 'Writer and researcher focused on world history, culture, and human behavior. His works are widely read in academia.',
                'website' => 'https://example.com/samuel-green',
                'cover' => 'samuel-green.jpg',
            ],
            [
                'name' => 'A. K. Sharma',
                'bio' => 'Academic and novelist with works exploring the intersection of technology, philosophy, and society.',
                'website' => 'https://example.com/ak-sharma',
                'cover' => 'ak-sharma.jpg',
            ],
            [
                'name' => 'Luna Verne',
                'bio' => 'Fiction author specializing in young adult, sci-fi, and fantasy genres with compelling, imaginative worlds.',
                'website' => 'https://example.com/luna-verne',
                'cover' => 'luna-verne.jpg',
            ],
            [
                'name' => 'Dr. Michael Rowan',
                'bio' => 'Historian and non-fiction author known for research on ancient civilizations and lost technologies.',
                'website' => 'https://example.com/michael-rowan',
                'cover' => 'michael-rowan.jpg',
            ],
            [
                'name' => 'Sofia Bennett',
                'bio' => 'Award-winning poet and essayist whose lyrical voice explores the beauty and pain of the human condition.',
                'website' => 'https://example.com/sofia-bennett',
                'cover' => 'sofia-bennett.jpg',
            ],
            [
                'name' => 'Ethan Park',
                'bio' => 'Modern mystery and thriller novelist, crafting fast-paced, character-driven stories set in urban backdrops.',
                'website' => 'https://example.com/ethan-park',
                'cover' => 'ethan-park.jpg',
            ],
        ];

        // Default placeholder image (stored in your seeder folder or public/images)
        $placeholderImage = database_path('seeders/placeholders/default-author.jpg');

        // Loop through each author and insert/update
        foreach ($authors as $author) {
            $record = Author::updateOrCreate(
                ['name' => $author['name']],
                [
                    'bio' => $author['bio'],
                    'website' => $author['website'],
                    'cover' => $author['cover'],
                ]
            );

            // Check and copy cover image (if not exists)
            $destPath = $imageDir . '/' . $author['cover'];

            if (!File::exists($destPath)) {
                try {
                    if (File::exists($placeholderImage)) {
                        File::copy($placeholderImage, $destPath);
                    } else {
                        // Create a simple text placeholder if default image missing
                        File::put($destPath, '');
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to copy placeholder image for author: ' . $author['name']);
                }
            }

            $this->command->info("Seeded author: {$record->name}");
        }

        $this->command->info('✅ AuthorSeeder completed successfully!');
    }
}
