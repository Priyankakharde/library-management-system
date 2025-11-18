<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        // create a few named students
        $named = [
            ['name'=>'Priyanka Kharde', 'email'=>'priyanka@example.com'],
            ['name'=>'John Doe', 'email'=>'john@example.com'],
            ['name'=>'Jane Smith', 'email'=>'jane@example.com'],
        ];

        foreach ($named as $n) {
            Student::create(array_merge($n, [
                'roll_no' => strtoupper('R-' . rand(1000,9999)),
                'department' => 'CS'
            ]));
        }

        // add random students
        Student::factory()->count(40)->create();
    }
}
