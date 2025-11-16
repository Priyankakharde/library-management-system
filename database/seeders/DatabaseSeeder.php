<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            \Database\Seeders\AuthorsTableSeeder::class,
            \Database\Seeders\BooksTableSeeder::class,
            // add your other seeders below if you have them
        ]);
    }
}
