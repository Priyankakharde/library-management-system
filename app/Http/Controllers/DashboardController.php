<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Student;
use App\Models\Issue;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Basic stats
        $totalBooks = Book::count();
        $totalStudents = Student::count();
        $issuedCount = Issue::whereNull('returned_at')->count();
        $overdueCount = Issue::whereNull('returned_at')
            ->whereDate('due_date', '<', Carbon::today())
            ->count();

        // Recently added books
        $recentBooks = Book::with('authors')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Most borrowed books (top 6)
        $mostBorrowedRaw = Issue::select('book_id', DB::raw('count(*) as borrow_count'))
            ->groupBy('book_id')
            ->orderByDesc('borrow_count')
            ->limit(6)
            ->get();

        $mostBorrowedBooks = collect();
        if ($mostBorrowedRaw->isNotEmpty()) {
            $bookIds = $mostBorrowedRaw->pluck('book_id')->toArray();
            $books = Book::with('authors')->whereIn('id', $bookIds)->get()->keyBy('id');

            foreach ($mostBorrowedRaw as $row) {
                if (isset($books[$row->book_id])) {
                    $b = $books[$row->book_id];
                    $b->borrow_count = (int) $row->borrow_count;
                    $mostBorrowedBooks->push($b);
                }
            }
            $mostBorrowedBooks = $mostBorrowedBooks->sortByDesc('borrow_count')->values();
        }

        // --- Issue/Return trend (30 days) ---
        $days = 30;
        $start = Carbon::today()->subDays($days - 1); // inclusive
        $labels = [];
        $issuesSeries = [];
        $returnsSeries = [];

        // Get counts grouped by date. Use date(created_at) which works on MySQL & Postgres.
        $issueCountsRaw = Issue::select(DB::raw("date(created_at) as dt"), DB::raw('count(*) as cnt'))
            ->whereDate('created_at', '>=', $start)
            ->groupBy('dt')
            ->get()
            ->pluck('cnt', 'dt')
            ->toArray();

        $returnCountsRaw = Issue::select(DB::raw("date(returned_at) as dt"), DB::raw('count(*) as cnt'))
            ->whereNotNull('returned_at')
            ->whereDate('returned_at', '>=', $start)
            ->groupBy('dt')
            ->get()
            ->pluck('cnt', 'dt')
            ->toArray();

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $key = $day->toDateString();
            $labels[] = $day->format('M j'); // short label
            $issuesSeries[] = isset($issueCountsRaw[$key]) ? (int)$issueCountsRaw[$key] : 0;
            $returnsSeries[] = isset($returnCountsRaw[$key]) ? (int)$returnCountsRaw[$key] : 0;
        }

        // Authors chart data (books per author)
        $authorsChart = Author::select('name', DB::raw('count(author_book.book_id) as books_count'))
            ->leftJoin('author_book', 'authors.id', '=', 'author_book.author_id')
            ->groupBy('authors.id', 'authors.name')
            ->orderByDesc('books_count')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalBooks',
            'totalStudents',
            'issuedCount',
            'overdueCount',
            'recentBooks',
            'mostBorrowedBooks',
            'labels',
            'issuesSeries',
            'returnsSeries',
            'authorsChart'
        ));
    }
}
