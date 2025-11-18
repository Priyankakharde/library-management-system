<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'student_id',
        'issued_at',
        'due_date',
        'returned_at',
        // add other fields your migration uses
    ];

    protected $dates = [
        'issued_at',
        'due_date',
        'returned_at',
        'created_at',
        'updated_at'
    ];

    // Relation to Book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relation to Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
