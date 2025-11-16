<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Support\Carbon;

class IssuesTableSeeder extends Seeder
{
    public function run()
    {
        if (!class_exists(Issue::class)) return;

        $book = Book::first();
        $student = Student::first();
        if ($book && $student) {
            Issue::create([
                'book_id' => $book->id,
                'student_id' => $student->id,
                'issued_at' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->subDays(2), // overdue
                'returned_at' => null
            ]);
        }
    }
}
