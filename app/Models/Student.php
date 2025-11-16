<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    /**
     * Fillable attributes.
     */
    protected $fillable = [
        'roll_no',
        'name',
        'course',
        'branch',
        'email',
        'phone',
        'avatar',
    ];

    /**
     * Casts.
     */
    protected $casts = [
        // add casts later if needed
    ];

    /**
     * Appended attributes for JSON.
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * Relationship: student has many issues (if Issue model exists).
     */
    public function issues()
    {
        return $this->hasMany(\App\Models\Issue::class, 'student_id', 'id');
    }

    /**
     * Accessor: avatar url (public/images/avatars preferred, then storage disk fallback).
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (empty($this->avatar)) {
            return asset('images/default-avatar.png');
        }

        // if absolute URL already
        if (Str::startsWith($this->avatar, ['http://', 'https://', '//'])) {
            return $this->avatar;
        }

        // check public/images/avatars
        $pub = public_path('images/avatars/' . $this->avatar);
        if (file_exists($pub)) {
            return asset('images/avatars/' . $this->avatar);
        }

        // fallback to storage/app/public
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        return asset('images/default-avatar.png');
    }

    /**
     * Scope: simple search by roll_no or name or email.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) return $query;
        $term = trim($term);
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('roll_no', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: filter by course or branch.
     */
    public function scopeByCourseBranch($query, $course = null, $branch = null)
    {
        if ($course) $query->where('course', $course);
        if ($branch) $query->where('branch', $branch);
        return $query;
    }

    /**
     * Booted: when deleting a student, try to delete avatar file from public/images/avatars and storage.
     */
    protected static function booted()
    {
        static::deleting(function (Student $student) {
            try {
                if (!empty($student->avatar)) {
                    $pub = public_path('images/avatars/' . $student->avatar);
                    if (file_exists($pub)) {
                        @unlink($pub);
                    }
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($student->avatar)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($student->avatar);
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
}
