<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'isbn',
        'published_at',
        'quantity',
        'cover_path',
        // add other fillable columns you use
    ];

    /**
     * Cast date fields so they become Carbon instances.
     */
    protected $casts = [
        'published_at' => 'date', // -> Carbon instance when accessed
        // 'created_at' and 'updated_at' are cast automatically by Eloquent
    ];

    /**
     * Relationship: book has many authors (many-to-many)
     */
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    // other relationships...
}
