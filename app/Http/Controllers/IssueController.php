<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Book;
use App\Models\Student;

class IssueController extends Controller
{
    public function index()
    {
        $issues = class_exists(Issue::class) ? Issue::with(['book','student'])->orderBy('issued_at','desc')->paginate(10) : collect();
        return view('issues.index', compact('issues'));
    }

    public function create()
    {
        $books = class_exists(Book::class) ? Book::all() : collect();
        $students = class_exists(Student::class) ? Student::all() : collect();
        return view('issues.create', compact('books','students'));
    }
}
