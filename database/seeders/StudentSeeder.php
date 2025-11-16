<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting StudentSeeder...');

        $avatarsDir = public_path('images/students');

        // Ensure folder exists
        if (! File::isDirectory($avatarsDir)) {
            try {
                File::makeDirectory($avatarsDir, 0755, true);
                $this->command->line("Created directory: {$avatarsDir}");
            } catch (\Throwable $e) {
                $this->command->warn("Could not create directory {$avatarsDir}: " . $e->getMessage());
            }
        }

        // Optional placeholder avatar used if you don't supply real avatar images
        $placeholder = database_path('seeders/placeholders/default-student.jpg');
        if (! File::exists($placeholder)) {
            // fallback to author placeholder if student placeholder missing
            $placeholder = database_path('seeders/placeholders/default-author.jpg');
        }

        $students = [
            [
                'roll_no' => 'S1001',
                'name' => 'Priya Kumar',
                'course' => 'B.Sc Computer Science',
                'branch' => 'Computer',
                'email' => 'priya.kumar@example.com',
                'phone' => '9876543210',
                'avatar' => 'priya.jpg',
            ],
            [
                'roll_no' => 'S1002',
                'name' => 'Rahul Sharma',
                'course' => 'B.Tech',
                'branch' => 'Information Technology',
                'email' => 'rahul.sharma@example.com',
                'phone' => '9876512345',
                'avatar' => 'rahul.jpg',
            ],
            [
                'roll_no' => 'S1003',
                'name' => 'Asha Patel',
                'course' => 'BCA',
                'branch' => 'Computer Applications',
                'email' => 'asha.patel@example.com',
                'phone' => '9812345670',
                'avatar' => 'asha.jpg',
            ],
            [
                'roll_no' => 'S1004',
                'name' => 'Vikram Singh',
                'course' => 'MCA',
                'branch' => 'Computer Applications',
                'email' => 'vikram.singh@example.com',
                'phone' => '9900112233',
                'avatar' => 'vikram.jpg',
            ],
        ];

        foreach ($students as $s) {
            try {
                // Use email if present as unique key; fallback to roll_no
                $match = !empty($s['email']) ? ['email' => $s['email']] : ['roll_no' => $s['roll_no']];

                $student = Student::updateOrCreate(
                    $match,
                    [
                        'roll_no' => $s['roll_no'] ?? null,
                        'name' => $s['name'],
                        'course' => $s['course'] ?? null,
                        'branch' => $s['branch'] ?? null,
                        'phone' => $s['phone'] ?? null,
                        'avatar' => !empty($s['avatar']) ? 'images/students/' . $s['avatar'] : null,
                    ]
                );

                // ensure avatar file exists so views don't 404
                if (!empty($s['avatar'])) {
                    $dest = $avatarsDir . '/' . $s['avatar'];
                    if (! File::exists($dest)) {
                        try {
                            if (File::exists($placeholder)) {
                                File::copy($placeholder, $dest);
                            } else {
                                File::put($dest, '');
                            }
                        } catch (\Throwable $e) {
                            Log::warning("StudentSeeder: failed to create avatar {$dest} for {$s['name']}: " . $e->getMessage());
                        }
                    }
                }

                $this->command->info("Seeded/updated student: {$student->name}");
            } catch (\Throwable $e) {
                Log::warning("StudentSeeder failed for {$s['name']}: " . $e->getMessage());
                $this->command->warn("Failed to seed student: {$s['name']}");
            }
        }

        $this->command->info('StudentSeeder completed.');
    }
}
