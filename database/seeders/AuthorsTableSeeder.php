<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorsTableSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Charles Dickens',
            'Jane Austen',
            'Mark Twain',
            'George Orwell',
            'F. Scott Fitzgerald',
            'J. K. Rowling',
            'Robert C. Martin',
            'Martin Fowler',
            'Andrew Hunt',
            'David Thomas'
        ];

        foreach ($names as $name) {
            Author::firstOrCreate(['name' => $name], ['email' => null]);
        }
    }
}
