<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // skip if an admin already exists with this email
        $email = 'admin@example.com';

        if (User::where('email', $email)->exists()) {
            $this->command->info('Admin user already exists: ' . $email);
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make('password123'), // change if you want
            'role' => 'librarian', // if your users table has role
            'remember_token' => Str::random(10),
        ]);

        $this->command->info('Created admin user: ' . $email . ' / password: password123');
    }
}
