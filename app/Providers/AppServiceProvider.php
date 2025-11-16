<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

use App\Models\Book;
use App\Models\Student;
use App\Models\Issue;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bindings or singletons can go here if needed in future.
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Try to set Bootstrap pagination (safe for multiple Laravel versions).
        try {
            if (method_exists(Paginator::class, 'useBootstrap')) {
                Paginator::useBootstrap();
            }
        } catch (\Throwable $e) {
            // ignore: paginator method may not exist on some older/newer versions
            Log::debug('Paginator bootstrap call skipped: ' . $e->getMessage());
        }

        // Blade helpers for role checks - first check role attribute, then method fallback.
        Blade::if('admin', function () {
            $user = auth()->user();
            if (! $user) {
                return false;
            }
            // Prefer role column if present
            if (isset($user->role)) {
                return in_array($user->role, ['admin', 'super-admin', 'administrator']);
            }
            // Fallback to method if defined on User model
            if (method_exists($user, 'isAdmin')) {
                try {
                    return (bool) $user->isAdmin();
                } catch (\Throwable $e) {
                    return false;
                }
            }

            return false;
        });

        Blade::if('librarian', function () {
            $user = auth()->user();
            if (! $user) {
                return false;
            }
            if (isset($user->role)) {
                return in_array($user->role, ['librarian', 'staff']);
            }
            if (method_exists($user, 'isLibrarian')) {
                try {
                    return (bool) $user->isLibrarian();
                } catch (\Throwable $e) {
                    return false;
                }
            }
            return false;
        });

        // Share common dashboard/header data with the 'layouts.app' view.
        View::composer('layouts.app', function ($view) {
            $data = [
                'shared_totalBooks'    => 0,
                'shared_totalStudents' => 0,
                'shared_issuedCount'   => 0,
                'shared_overdueCount'  => 0,
                'shared_recentBooks'   => collect(),
            ];

            try {
                // BOOKS: total and recent (guard with class & table existence)
                if (class_exists(Book::class) && Schema::hasTable((new Book())->getTable())) {
                    $data['shared_totalBooks'] = Book::count();

                    // if Book has a relation 'author' it will eager load; if not, it's harmless
                    $data['shared_recentBooks'] = Book::orderByDesc('created_at')->limit(5)->get();
                }

                // STUDENTS: total
                if (class_exists(Student::class) && Schema::hasTable((new Student())->getTable())) {
                    $data['shared_totalStudents'] = Student::count();
                }

                // ISSUES: compute issued count and overdue count in a backwards-compatible way
                if (class_exists(Issue::class) && Schema::hasTable((new Issue())->getTable())) {
                    $issueTable = (new Issue())->getTable();

                    // Decide how to interpret "currently issued":
                    // Common possibilities: 'status' column with 'issued', or boolean 'returned', or timestamp 'returned_at'
                    if (Schema::hasColumn($issueTable, 'status')) {
                        // status column exists: treat 'issued' as currently issued
                        $data['shared_issuedCount'] = Issue::where('status', 'issued')->count();

                        // due date column may be 'due_date' or 'return_date'
                        $dueCol = Schema::hasColumn($issueTable, 'due_date') ? 'due_date'
                                : (Schema::hasColumn($issueTable, 'return_date') ? 'return_date' : null);

                        if ($dueCol) {
                            $data['shared_overdueCount'] = Issue::where('status', 'issued')
                                ->whereDate($dueCol, '<', now()->toDateString())
                                ->count();
                        }
                    } elseif (Schema::hasColumn($issueTable, 'returned')) {
                        // boolean 'returned' column (true when returned)
                        $data['shared_issuedCount'] = Issue::where('returned', false)->count();

                        $dueCol = Schema::hasColumn($issueTable, 'due_date') ? 'due_date'
                                : (Schema::hasColumn($issueTable, 'return_date') ? 'return_date' : null);

                        if ($dueCol) {
                            $data['shared_overdueCount'] = Issue::where('returned', false)
                                ->whereDate($dueCol, '<', now()->toDateString())
                                ->count();
                        }
                    } elseif (Schema::hasColumn($issueTable, 'returned_at')) {
                        // timestamp 'returned_at' column (NULL means not returned)
                        $data['shared_issuedCount'] = Issue::whereNull('returned_at')->count();

                        $dueCol = Schema::hasColumn($issueTable, 'due_date') ? 'due_date'
                                : (Schema::hasColumn($issueTable, 'return_date') ? 'return_date' : null);

                        if ($dueCol) {
                            $data['shared_overdueCount'] = Issue::whereNull('returned_at')
                                ->whereDate($dueCol, '<', now()->toDateString())
                                ->count();
                        }
                    } else {
                        // Fallback: try null 'return_date' or 'returned' boolean not present. Assume none issued.
                        $data['shared_issuedCount'] = 0;
                        $data['shared_overdueCount'] = 0;
                    }
                }
            } catch (\Throwable $e) {
                // Log debug but do not break the page render.
                Log::debug('AppServiceProvider view composer error: ' . $e->getMessage());
            }

            $view->with($data);
        });
    }
}
