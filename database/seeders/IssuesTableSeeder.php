<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Book;
use App\Models\Student;
use Carbon\Carbon;

class IssuesTableSeeder extends Seeder
{
    public function run()
    {
        // ensure some books and students exist
        $books = Book::all();
        $students = Student::all();

        if ($books->isEmpty() || $students->isEmpty()) {
            $this->command->info('No books or students found for issues seeder.');
            return;
        }

        // create a mixture of issues: some returned, some outstanding, some overdue
        for ($i = 0; $i < 30; $i++) {
            $book = $books->random();
            $student = $students->random();

            $issuedAt = Carbon::now()->subDays(rand(1, 45));
            $dueDate = (clone $issuedAt)->addDays(14);
            $returned = rand(0,1);

            $returnedAt = $returned ? $issuedAt->copy()->addDays(rand(1,20)) : null;

            Issue::create([
                'book_id' => $book->id,
                'student_id' => $student->id,
                'issued_at' => $issuedAt,
                'due_date' => $dueDate->toDateString(),
                'returned_at' => $returnedAt,
            ]);
        }

        // create a few overdue issues specifically
        $overdueBooks = $books->take(5);
        foreach ($overdueBooks as $book) {
            Issue::create([
                'book_id' => $book->id,
                'student_id' => $students->random()->id,
                'issued_at' => Carbon::now()->subDays(30),
                'due_date' => Carbon::now()->subDays(16)->toDateString(),
                'returned_at' => null,
            ]);
        }
    }
}
