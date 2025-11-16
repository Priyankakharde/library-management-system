<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class IssueRecord extends Model
{
    protected $fillable = ['book_id','student_id','issue_date','due_date','returned_at'];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
    ];

    public function book(): BelongsTo { return $this->belongsTo(Book::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }

    public function scopePending(Builder $q): Builder {
        return $q->whereNull('returned_at');
    }

    public function scopeOverdue(Builder $q): Builder {
        return $q->whereNull('returned_at')->where('due_date', '<', now()->toDateString());
    }
}
