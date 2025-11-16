<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BooksTableSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'title' => 'A Tale of Two Cities',
                'isbn' => '9780141439600',
                'quantity' => 2,
                'authors' => ['Charles Dickens'],
            ],
            [
                'title' => 'Pride and Prejudice',
                'isbn' => '9780143105428',
                'quantity' => 3,
                'authors' => ['Jane Austen'],
            ],
            [
                'title' => 'Adventures of Huckleberry Finn',
                'isbn' => '9780486280615',
                'quantity' => 1,
                'authors' => ['Mark Twain'],
            ],
            [
                'title' => 'Clean Code',
                'isbn' => '9780132350884',
                'quantity' => 1,
                'authors' => ['Robert C. Martin'],
            ],
            [
                'title' => 'Refactoring',
                'isbn' => '9780201485677',
                'quantity' => 1,
                'authors' => ['Martin Fowler'],
            ],
        ];

        foreach ($samples as $s) {
            $book = Book::firstOrCreate(
                ['title' => $s['title']],
                ['isbn' => $s['isbn'] ?? null, 'quantity' => $s['quantity'] ?? 1]
            );

            // attach authors (ensure authors exist)
            $authorIds = [];
            foreach ($s['authors'] as $name) {
                $author = Author::firstOrCreate(['name' => $name], ['email' => null]);
                $authorIds[] = $author->id;
            }

            if (!empty($authorIds)) {
                $book->authors()->syncWithoutDetaching($authorIds);
            }
        }
    }
}
