<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Issue
 *
 * Fields expected on issues table (recommended):
 * - id
 * - book_id (unsignedBigInteger) -> books.id
 * - student_id (unsignedBigInteger) -> students.id
 * - issued_by (unsignedBigInteger, nullable) -> users.id (who issued)
 * - issue_date (date)
 * - due_date (date)
 * - returned_at (nullable datetime)
 * - returned_by (unsignedBigInteger, nullable) -> users.id (who marked returned)
 * - status (string) optional: 'issued'|'returned'
 * - notes (text) optional
 * - created_at, updated_at
 *
 * This model includes useful scopes and helpers used by controllers provided earlier.
 */
class Issue extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'book_id',
        'student_id',
        'issued_by',
        'issue_date',
        'due_date',
        'returned_at',
        'returned_by',
        'status',
        'notes',
    ];

    /**
     * Date / datetime casts
     */
    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'returned_at' => 'datetime',
    ];

    /**
     * Default attributes
     */
    protected $attributes = [
        // If you want to use status column: default to 'issued'
        'status' => 'issued',
    ];

    /**
     * Relationship: the book which was issued.
     */
    public function book()
    {
        return $this->belongsTo(\App\Models\Book::class, 'book_id', 'id');
    }

    /**
     * Relationship: the student who received the book.
     */
    public function student()
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id', 'id');
    }

    /**
     * Relationship: the user who issued the book (staff).
     */
    public function issuer()
    {
        return $this->belongsTo(\App\Models\User::class, 'issued_by', 'id');
    }

    /**
     * Relationship: the user who marked returned (staff).
     */
    public function returner()
    {
        return $this->belongsTo(\App\Models\User::class, 'returned_by', 'id');
    }

    /**
     * Scope: only currently issued (not returned) records.
     */
    public function scopeIssued($query)
    {
        // prefer status column; otherwise returned_at null
        if (\Schema::hasColumn($this->getTable(), 'status')) {
            return $query->where('status', 'issued');
        }
        return $query->whereNull('returned_at');
    }

    /**
     * Scope: only returned records.
     */
    public function scopeReturned($query)
    {
        if (\Schema::hasColumn($this->getTable(), 'status')) {
            return $query->where('status', 'returned');
        }
        return $query->whereNotNull('returned_at');
    }

    /**
     * Scope: overdue (due_date less than today and still issued).
     */
    public function scopeOverdue($query)
    {
        $today = Carbon::today()->toDateString();

        $q = $query->where(function ($q2) use ($today) {
            $q2->where('due_date', '<', $today);
        });

        if (\Schema::hasColumn($this->getTable(), 'status')) {
            $q->where('status', 'issued');
        } else {
            $q->whereNull('returned_at');
        }

        return $q;
    }

    /**
     * Scope: recent issues
     */
    public function scopeRecent($query, $limit = 8)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    /**
     * Is this issue currently outstanding (not returned)?
     *
     * @return bool
     */
    public function isIssued(): bool
    {
        if (\Schema::hasColumn($this->getTable(), 'status')) {
            return ($this->status ?? '') === 'issued';
        }
        return is_null($this->returned_at);
    }

    /**
     * Is this issue overdue?
     *
     * @return bool
     */
    public function isOverdue(): bool
    {
        if (empty($this->due_date)) {
            return false;
        }
        return Carbon::today()->gt(Carbon::parse($this->due_date)) && $this->isIssued();
    }

    /**
     * Days overdue (0 if not overdue).
     *
     * @return int
     */
    public function daysOverdue(): int
    {
        if (! $this->isOverdue() || empty($this->due_date)) {
            return 0;
        }
        return Carbon::today()->diffInDays(Carbon::parse($this->due_date));
    }

    /**
     * Mark this issue as returned.
     * - sets returned_at and returned_by (if provided)
     * - updates status column if present
     * - increments book quantity (if book model has quantity column)
     *
     * This method performs best-effort actions and will not throw if book missing.
     *
     * @param int|null $returnedBy user id who processed return (optional)
     * @param \DateTime|string|null $returnedAt explicit returned time (optional)
     * @return $this
     */
    public function markReturned($returnedBy = null, $returnedAt = null)
    {
        // If already returned, do nothing
        if (! $this->isIssued()) {
            return $this;
        }

        $this->returned_at = $returnedAt ? Carbon::parse($returnedAt) : Carbon::now();
        if ($returnedBy) {
            $this->returned_by = $returnedBy;
        }

        if (\Schema::hasColumn($this->getTable(), 'status')) {
            $this->status = 'returned';
        }

        // Save first so timestamps updated
        $this->save();

        // Try to increment book quantity if available
        try {
            if ($this->relationLoaded('book') ? $this->book : $this->book()->exists()) {
                $book = $this->book()->first();
                if ($book) {
                    // If book has quantity column, increment; otherwise skip
                    if (\Schema::hasColumn($book->getTable(), 'quantity')) {
                        $book->increment('quantity', 1);
                    }
                }
            }
        } catch (\Throwable $e) {
            // ignore book update errors
        }

        return $this;
    }

    /**
     * Create an Issue record and adjust book quantity atomically.
     *
     * This is a convenience static helper used by controllers when issuing.
     * It will check stock, create the issue and decrement book quantity inside a transaction.
     *
     * @param int $bookId
     * @param int $studentId
     * @param int|null $issuedBy
     * @param \DateTime|string|null $issueDate
     * @param \DateTime|string|null $dueDate
     * @param string|null $notes
     * @return Issue
     *
     * @throws \Exception when not enough copies or DB error
     */
    public static function createAndIssue($bookId, $studentId, $issuedBy = null, $issueDate = null, $dueDate = null, $notes = null)
    {
        return \DB::transaction(function () use ($bookId, $studentId, $issuedBy, $issueDate, $dueDate, $notes) {
            $book = \App\Models\Book::lockForUpdate()->findOrFail($bookId);

            // If quantity column exists and no copies available, throw
            if (\Schema::hasColumn($book->getTable(), 'quantity') && ((int) $book->quantity) <= 0) {
                throw new \RuntimeException('No copies available to issue.');
            }

            // decrement quantity if column exists
            if (\Schema::hasColumn($book->getTable(), 'quantity')) {
                $book->decrement('quantity', 1);
            }

            $issue = static::create([
                'book_id'    => $book->id,
                'student_id' => $studentId,
                'issued_by'  => $issuedBy,
                'issue_date' => $issueDate ? Carbon::parse($issueDate)->toDateString() : Carbon::today()->toDateString(),
                'due_date'   => $dueDate ? Carbon::parse($dueDate)->toDateString() : Carbon::today()->addWeeks(2)->toDateString(),
                'status'     => \Schema::hasColumn((new static)->getTable(), 'status') ? 'issued' : null,
                'notes'      => $notes,
            ]);

            return $issue;
        });
    }

    /**
     * Booted model events.
     * - When deleting an issue that is still issued, attempt to increment book quantity (best effort).
     */
    protected static function booted()
    {
        static::deleting(function (Issue $issue) {
            try {
                if ($issue->isIssued() && $issue->book) {
                    // If book has quantity column, increment (we're deleting an outstanding record)
                    if (\Schema::hasColumn($issue->book->getTable(), 'quantity')) {
                        $issue->book->increment('quantity', 1);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
}
