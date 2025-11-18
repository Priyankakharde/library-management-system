<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorsTableSeeder extends Seeder
{
    public function run()
    {
        $list = [
            'Charles Dickens','Jane Austen','Mark Twain','Robert C. Martin',
            'Martin Fowler','George Orwell','Aldous Huxley','J.K. Rowling'
        ];

        foreach ($list as $name) {
            Author::create(['name'=>$name]);
        }

        // some extra random authors
        Author::factory()->count(8)->create();
    }
}
