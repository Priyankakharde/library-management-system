<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $students = Student::query()
            ->when($q, fn($qry) => $qry->where('name', 'ilike', "%{$q}%")
                ->orWhere('email', 'ilike', "%{$q}%")
                ->orWhere('roll_no', 'ilike', "%{$q}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('students.index', compact('students', 'q'));
    }

    /**
     * Show form to create a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:191',
            'email'   => 'nullable|email|max:191|unique:students,email',
            'roll_no' => 'nullable|string|max:100|unique:students,roll_no',
        ]);

        Student::create($data);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully.');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'name'    => 'required|string|max:191',
            'email'   => ['nullable','email','max:191', Rule::unique('students','email')->ignore($student->id)],
            'roll_no' => ['nullable','string','max:100', Rule::unique('students','roll_no')->ignore($student->id)],
        ]);

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }
}
