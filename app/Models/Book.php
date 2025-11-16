<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',       // legacy text
        'published_at',
        'isbn',
        'quantity',
        'publisher',
        'edition',
        'pages',
        'language',
        'genre',
        'location',
        'description',
        'cover_image',
    ];

    protected $dates = ['published_at'];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }

    public function getAuthorNamesAttribute()
    {
        return $this->authors->pluck('name')->implode(', ');
    }

    // optional helper to get cover url
    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }
}
