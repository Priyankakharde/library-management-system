<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Arr;

class BooksTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure authors exist
        $authors = Author::all();
        if ($authors->isEmpty()) {
            $this->command->info('No authors found — create authors first.');
            return;
        }

        // Explicit demo books (some with multiple authors)
        $demo = [
            ['title'=>'A Tale of Two Cities','authors'=>['Charles Dickens'],'qty'=>5],
            ['title'=>'Pride and Prejudice','authors'=>['Jane Austen'],'qty'=>7],
            ['title'=>'Adventures of Huckleberry Finn','authors'=>['Mark Twain'],'qty'=>4],
            ['title'=>'Clean Code','authors'=>['Robert C. Martin'],'qty'=>3],
            ['title'=>'Refactoring','authors'=>['Martin Fowler','Robert C. Martin'],'qty'=>2],
            ['title'=>'1984','authors'=>['George Orwell'],'qty'=>6],
            ['title'=>'Brave New World','authors'=>['Aldous Huxley'],'qty'=>5],
            ['title'=>'Harry Potter and the Philosopher\'s Stone','authors'=>['J.K. Rowling'],'qty'=>8],
        ];

        foreach ($demo as $d) {
            $book = Book::create([
                'title'=>$d['title'],
                'isbn'=>null,
                'published_at'=>now()->subYears(rand(1,20))->toDateString(),
                'quantity'=>$d['qty'],
            ]);

            // attach authors by name
            $authorIds = Author::whereIn('name', $d['authors'])->pluck('id')->toArray();

            // if some author names missing, fill randomly
            if (empty($authorIds)) {
                $authorIds = Author::inRandomOrder()->limit(1)->pluck('id')->toArray();
            }

            $book->authors()->sync($authorIds);
        }

        // create some more random books and link to 1-3 random authors
        for ($i=0;$i<18;$i++) {
            $title = 'Demo Book ' . ($i+1);
            $book = Book::create([
                'title'=>$title,
                'isbn'=>null,
                'published_at'=>now()->subDays(rand(1,1000))->toDateString(),
                'quantity'=>rand(1,12),
            ]);
            $pick = Author::inRandomOrder()->take(rand(1,3))->pluck('id')->toArray();
            $book->authors()->sync($pick);
        }
    }
}
