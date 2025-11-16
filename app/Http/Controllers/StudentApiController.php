<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    /**
     * Return paginated JSON list of students with search.
     */
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));

        $query = Student::query();

        if ($q !== '') {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('student_id', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
        }

        $perPage = (int) $request->query('per_page', 15);
        $students = $query->orderBy('name')->paginate($perPage)->appends($request->query());

        return response()->json($students);
    }

    /**
     * Return a single student as JSON.
     */
    public function show(Student $student)
    {
        return response()->json($student);
    }
}
