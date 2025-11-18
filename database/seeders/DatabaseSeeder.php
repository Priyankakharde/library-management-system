<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user (dev)
        User::updateOrCreate(
            ['email'=>'admin@lms.test'],
            ['name'=>'Admin','password'=>'password']
        );

        // Run authors and books seeders
        $this->call([
            \Database\Seeders\AuthorsTableSeeder::class,
            \Database\Seeders\BooksTableSeeder::class,
        ]);
    }
}
