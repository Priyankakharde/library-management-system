<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

/**
 * Application AuthServiceProvider
 *
 * Defines gates used across the Library Management System. This file is
 * defensive: it prefers calling helper methods on the User model (isAdmin/isLibrarian)
 * but will fall back to checking the 'role' attribute if those methods are missing.
 *
 * Drop this file in place of your existing App\Providers\AuthServiceProvider.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
        // Global "before" check: if user is admin, grant all abilities.
        // This short-circuits other gate checks so admin acts as super-user.
        //
        Gate::before(function ($user, $ability) {
            try {
                if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
                    return true;
                }
                // fallback to role property if method not available
                if (isset($user->role) && (string) $user->role === User::ROLE_ADMIN) {
                    return true;
                }
            } catch (\Throwable $e) {
                // ignore and continue to normal gate checks
            }

            return null; // continue to other gate checks
        });

        //
        // Gate: isAdmin - true only for admin users (super-user)
        //
        Gate::define('isAdmin', function ($user) {
            try {
                if (method_exists($user, 'isAdmin')) {
                    return $user->isAdmin();
                }
                return isset($user->role) && (string) $user->role === User::ROLE_ADMIN;
            } catch (\Throwable $e) {
                return false;
            }
        });

        //
        // Gate: isLibrarian - true for librarian and admin roles
        //
        Gate::define('isLibrarian', function ($user) {
            try {
                if (method_exists($user, 'isLibrarian')) {
                    return $user->isLibrarian();
                }
                return isset($user->role) && in_array((string) $user->role, [User::ROLE_LIBRARIAN, User::ROLE_ADMIN], true);
            } catch (\Throwable $e) {
                return false;
            }
        });

        //
        // Additional example gates you may find useful (comment/uncomment as needed)
        //
        // Gate::define('manage-books', fn($user) => Gate::allows('isLibrarian'));
        // Gate::define('manage-students', fn($user) => Gate::allows('isLibrarian'));
    }
}
