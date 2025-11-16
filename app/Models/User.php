<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\User
 *
 * A full-featured user model for the Library Management System.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Common role values used across controllers/gates
    public const ROLE_ADMIN     = 'admin';
    public const ROLE_LIBRARIAN = 'librarian';
    public const ROLE_USER      = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // admin / librarian / user
        'avatar',    // filename or URL for avatar
    ];

    /**
     * The attributes that should be hidden for arrays / JSON.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attribute casts.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Appended accessors for JSON output.
     *
     * @var array<int,string>
     */
    protected $appends = [
        'avatar_url',
    ];

    /* ---------------------------
     | Relationships
     |---------------------------- */

    /**
     * Issues created/issued by this user (issued_by).
     */
    public function issuedIssues()
    {
        return $this->hasMany(Issue::class, 'issued_by', 'id');
    }

    /**
     * Issues marked returned by this user (returned_by).
     */
    public function returnedIssues()
    {
        return $this->hasMany(Issue::class, 'returned_by', 'id');
    }

    /* ---------------------------
     | Accessors / Mutators
     |---------------------------- */

    /**
     * Mutator: automatically hash password when set.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        // If already hashed or empty, set as-is. Otherwise hash.
        if (empty($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        if (Str::startsWith($value, '$2y$') && strlen($value) === 60) {
            // likely already a bcrypt hash
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    /**
     * Accessor: return usable avatar URL.
     * Prefers public/images/avatars/{avatar} then storage disk 'public', then placeholder.
     *
     * @return string
     */
    public function getAvatarUrlAttribute(): string
    {
        if (empty($this->avatar)) {
            return asset('images/default-avatar.png');
        }

        // If it's already an absolute URL, return it
        if (Str::startsWith($this->avatar, ['http://', 'https://', '//'])) {
            return $this->avatar;
        }

        // public/images/avatars
        $publicPath = public_path('images/avatars/' . $this->avatar);
        if (file_exists($publicPath) && is_file($publicPath)) {
            return asset('images/avatars/' . $this->avatar);
        }

        // storage/app/public
        if (Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        // fallback
        return asset('images/default-avatar.png');
    }

    /* ---------------------------
     | Role helpers
     |---------------------------- */

    /**
     * Is the user an admin?
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (string) $this->role === self::ROLE_ADMIN;
    }

    /**
     * Is the user a librarian (or admin)?
     *
     * @return bool
     */
    public function isLibrarian(): bool
    {
        return in_array((string) $this->role, [self::ROLE_LIBRARIAN, self::ROLE_ADMIN], true);
    }

    /* ---------------------------
     | Convenience / utilities
     |---------------------------- */

    /**
     * Safely delete avatar files when user deleted.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function (User $user) {
            try {
                if (! empty($user->avatar)) {
                    $pub = public_path('images/avatars/' . $user->avatar);
                    if (file_exists($pub) && is_file($pub)) {
                        @unlink($pub);
                    }

                    if (Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                }
            } catch (\Throwable $e) {
                // ignore file system errors
            }
        });
    }
}
