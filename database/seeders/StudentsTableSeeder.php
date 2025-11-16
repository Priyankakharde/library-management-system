<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        if (!class_exists(Student::class)) return;
        Student::create(['name'=>'Alice','email'=>'alice@example.com']);
        Student::create(['name'=>'Bob','email'=>'bob@example.com']);
    }
}
