<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Book;
use App\Models\Author;
use App\Models\Issue;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // count books if table exists
            $totalBooks = Schema::hasTable('books') ? Book::count() : 0;

            // safe students count: only try if Student model exists AND table exists
            $totalStudents = 0;
            if (class_exists(\App\Models\Student::class) && Schema::hasTable('students')) {
                $totalStudents = \App\Models\Student::count();
            }

            // issued books and overdue
            $issuedBooks = Schema::hasTable('issues') ? Issue::whereNull('returned_at')->count() : 0;

            $overdueBooks = 0;
            if (Schema::hasTable('issues')) {
                $overdueBooks = Issue::whereNull('returned_at')
                    ->whereDate('due_date', '<', now()->toDateString())
                    ->count();
            }

            // authors + counts (safe)
            $authors = [];
            $booksCount = [];
            if (class_exists(Author::class) && Schema::hasTable('authors')) {
                $authorsWithCount = Author::withCount('books')->orderBy('books_count', 'desc')->get();
                $top = $authorsWithCount->take(15);
                $authors = $top->pluck('name')->map(function ($name) {
                    return strlen($name) > 40 ? substr($name, 0, 37) . '...' : $name;
                })->toArray();
                $booksCount = $top->pluck('books_count')->toArray();
            }

            return view('dashboard', compact(
                'totalBooks',
                'totalStudents',
                'issuedBooks',
                'overdueBooks',
                'authors',
                'booksCount'
            ));
        } catch (\Throwable $e) {
            // log and return minimal view so user doesn't see blank page
            Log::error('Dashboard render error: '.$e->getMessage());
            // fallback minimal data
            return view('dashboard', [
                'totalBooks' => 0,
                'totalStudents' => 0,
                'issuedBooks' => 0,
                'overdueBooks' => 0,
                'authors' => [],
                'booksCount' => []
            ]);
        }
    }
}
