<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        $students = class_exists(Student::class) ? Student::orderBy('created_at','desc')->paginate(10) : collect();
        return view('students.index', compact('students'));
    }
}
