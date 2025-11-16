<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Issue;
use App\Models\Book;
use App\Models\Student;
use App\Models\User;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting IssueSeeder...');

        // Quick collections of existing books and students
        $books = Book::orderBy('id')->get();
        $students = Student::orderBy('id')->get();
        $adminUser = User::where('role', 'admin')->first() ?: User::first();

        if ($books->isEmpty() || $students->isEmpty()) {
            $this->command->warn("No books or students found. Run BookSeeder and StudentSeeder first.");
            return;
        }

        // Create a few example issues (some returned, some still issued)
        $now = now();
        $examples = [
            // book index 0 to student index 0, issued 10 days ago, due 3 days ago (overdue)
            [
                'book' => $books->get(0),
                'student' => $students->get(0),
                'issue_date' => $now->copy()->subDays(10)->toDateString(),
                'due_date' => $now->copy()->subDays(3)->toDateString(),
                'status' => 'issued',
                'returned_at' => null,
            ],
            // book index 1 to student index 1, issued 7 days ago, returned 2 days ago
            [
                'book' => $books->get(1),
                'student' => $students->get(1),
                'issue_date' => $now->copy()->subDays(7)->toDateString(),
                'due_date' => $now->copy()->subDays(1)->toDateString(),
                'status' => 'returned',
                'returned_at' => $now->copy()->subDays(2)->setTime(12,0,0),
            ],
            // book index 2 to student index 2, issued today, due in 7 days
            [
                'book' => $books->get(2),
                'student' => $students->get(2),
                'issue_date' => $now->toDateString(),
                'due_date' => $now->copy()->addDays(7)->toDateString(),
                'status' => 'issued',
                'returned_at' => null,
            ],
        ];

        foreach ($examples as $ex) {
            try {
                /** @var Book $book */
                $book = $ex['book'];
                /** @var Student $student */
                $student = $ex['student'];

                if (!$book || !$student) {
                    continue;
                }

                // Avoid duplicate by checking same book, student and issue_date
                $existing = Issue::where('book_id', $book->id)
                    ->where('student_id', $student->id)
                    ->whereDate('issue_date', $ex['issue_date'])
                    ->first();

                if ($existing) {
                    $this->command->line("Issue already exists for book '{$book->title}' and student '{$student->name}' on {$ex['issue_date']}, skipping.");
                    continue;
                }

                // Create the issue entry
                $issue = Issue::create([
                    'book_id' => $book->id,
                    'student_id' => $student->id,
                    'issued_by' => $adminUser?->id,
                    'issue_date' => $ex['issue_date'],
                    'due_date' => $ex['due_date'],
                    'returned_at' => $ex['returned_at'] ? $ex['returned_at'] : null,
                    'returned_by' => $ex['returned_at'] ? ($adminUser?->id) : null,
                    'status' => $ex['status'],
                    'notes' => 'Seeded demo record',
                ]);

                // Adjust book quantity if issuing (decrement) and if book has a quantity column
                if ($issue && $ex['status'] === 'issued') {
                    try {
                        if (isset($book->quantity) && is_numeric($book->quantity) && $book->quantity > 0) {
                            $book->decrement('quantity', 1);
                        }
                    } catch (\Throwable $e) {
                        // don't fail seeding if quantity update fails
                        Log::warning('IssueSeeder: failed to decrement quantity for book id ' . $book->id . ': ' . $e->getMessage());
                    }
                }

                // If status is returned and quantity is present, increment to reflect return
                if ($issue && $ex['status'] === 'returned') {
                    try {
                        if (isset($book->quantity) && is_numeric($book->quantity)) {
                            $book->increment('quantity', 1);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('IssueSeeder: failed to increment quantity for book id ' . $book->id . ': ' . $e->getMessage());
                    }
                }

                $this->command->info("Created issue record for book '{$book->title}' -> student '{$student->name}' (status: {$issue->status}).");
            } catch (\Throwable $e) {
                Log::warning('IssueSeeder failed: ' . $e->getMessage());
                $this->command->warn("Failed to create example issue: " . ($ex['book']->title ?? 'unknown'));
            }
        }

        $this->command->info('IssueSeeder completed.');
    }
}
