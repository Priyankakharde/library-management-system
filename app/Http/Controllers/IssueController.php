<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Book;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IssueController extends Controller
{
    /**
     * Index - list issues (optional)
     */
    public function index(Request $request)
    {
        $issues = Issue::with(['book', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('issues.index', compact('issues'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        // Load students and books (only books with quantity > 0 recommended)
        $students = Student::orderBy('name')->get();

        // Show all books but mark those with zero quantity as disabled in UI
        $books = Book::orderBy('title')->get();

        return view('issues.create', compact('students', 'books'));
    }

    /**
     * Store the issued book.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id'    => 'required|exists:books,id',
            'due_date'   => 'required|date|after_or_equal:today',
        ]);

        // Use transaction - we'll update book quantity and create issue atomically
        DB::beginTransaction();
        try {
            $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

            if ($book->quantity <= 0) {
                DB::rollBack();
                return back()->withInput()->withErrors(['book_id' => 'Selected book is out of stock.']);
            }

            // Create the issue
            $issue = Issue::create([
                'student_id' => $validated['student_id'],
                'book_id'    => $book->id,
                'due_date'   => Carbon::parse($validated['due_date'])->endOfDay(),
                'issued_at'  => Carbon::now(),
            ]);

            // Decrement book quantity
            $book->quantity = max(0, $book->quantity - 1);
            $book->save();

            DB::commit();

            return redirect()->route('issues.index')->with('success', 'Book issued successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log error in production; for debugging return error message
            return back()->withInput()->withErrors(['error' => 'An error occurred while issuing the book: '.$e->getMessage()]);
        }
    }

    /**
     * (Optional) show return page
     */
    public function return($id)
    {
        $issue = Issue::with('book','student')->findOrFail($id);
        return view('issues.return', compact('issue'));
    }

    /**
     * (Optional) process return
     */
    public function returnSave(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);

        if ($issue->returned_at) {
            return back()->with('success', 'This book is already returned.');
        }

        DB::beginTransaction();
        try {
            $issue->returned_at = Carbon::now();
            $issue->save();

            // increment book quantity
            $book = $issue->book;
            $book->quantity = ($book->quantity ?? 0) + 1;
            $book->save();

            DB::commit();
            return redirect()->route('issues.index')->with('success','Book returned successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to return book: '.$e->getMessage()]);
        }
    }
}
