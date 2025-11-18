<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * Fillable columns — match your migration.
     */
    protected $fillable = [
        'roll_no',
        'name',
        'email',
        'phone',
    ];

    /**
     * Relationship:
     * A student has many issued books.
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Simple search (name, roll_no, email)
     */
    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('roll_no', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
