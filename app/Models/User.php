<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // If your users table uses a different primary key, update here.
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Hidden fields when serializing
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash password when setting.
     */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        // if already hashed (starts with $2y$), keep as-is
        if (substr($value, 0, 4) === '$2y$' || substr($value, 0, 4) === '$argon') {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
// Replace or create admin user with known password 'password'
\App\Models\User::updateOrCreate(
    ['email' => 'admin@lms.test'],
    [
        'name' => 'Admin',
        'password' => 'password' // mutator will hash it
    ]
);

