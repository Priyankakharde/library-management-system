<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;
use App\Models\Student;
use Carbon\Carbon;

class IssueFactory extends Factory
{
    protected $model = \App\Models\Issue::class;

    public function definition()
    {
        $bookId = Book::inRandomOrder()->value('id') ?? Book::factory()->create()->id;
        $studentId = Student::inRandomOrder()->value('id') ?? Student::factory()->create()->id;

        // issued date within last 40 days
        $issuedAt = $this->faker->dateTimeBetween('-40 days', 'now');
        $dueDate = (clone $issuedAt)->modify('+14 days');

        // randomly mark some as returned
        $returned = $this->faker->boolean(50);
        $returnedAt = $returned ? $this->faker->dateTimeBetween($issuedAt, 'now') : null;

        return [
            'book_id' => $bookId,
            'student_id' => $studentId,
            'issued_at' => Carbon::instance($issuedAt),
            'due_date' => Carbon::instance($dueDate)->toDateString(),
            'returned_at' => $returnedAt ? Carbon::instance($returnedAt) : null,
        ];
    }
}
