<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Console\Scheduling\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| Here you may define all of your Closure based console commands. Each
| Closure is bound to a command instance allowing a simple approach
| to interacting with each command's IO (via $this->info / $this->error).
|
| This file adds a few helpful LMS-specific commands that are safe to run
| in development and on servers (they call built-in Artisan commands).
|
*/

/**
 * Default example (Laravel default).
 * Usage: php artisan inspire
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Clear common caches used during development or after deploy.
 * Usage: php artisan lms:clear
 */
Artisan::command('lms:clear', function () {
    $this->info('Clearing application cache...');
    Artisan::call('cache:clear');
    $this->comment(trim(Artisan::output()));

    $this->info('Clearing route cache...');
    Artisan::call('route:clear');
    $this->comment(trim(Artisan::output()));

    $this->info('Clearing config cache...');
    Artisan::call('config:clear');
    $this->comment(trim(Artisan::output()));

    $this->info('Clearing compiled views...');
    Artisan::call('view:clear');
    $this->comment(trim(Artisan::output()));

    $this->info('Done — caches cleared.');
})->describe('Clear common application caches (cache, route, config, view)');

/**
 * List all registered routes and print them in console (same as php artisan route:list).
 * Usage: php artisan lms:routes
 */
Artisan::command('lms:routes', function () {
    $this->info('Listing registered routes (route:list):');
    // call the built-in route:list and display its output
    Artisan::call('route:list', ['--columns' => 'method,uri,name,action,middleware']);
    $this->line(Artisan::output());
})->describe('Display the application routes (alias for route:list)');

/**
 * Quick status check for the LMS app (DB connection + environment).
 * Usage: php artisan lms:status
 */
Artisan::command('lms:status', function () {
    $this->info('LMS quick status check');

    // Show basic app env
    $this->line('APP_ENV: ' . config('app.env'));
    $this->line('APP_DEBUG: ' . (config('app.debug') ? 'true' : 'false'));

    // Test DB connection (safe)
    try {
        DB::connection()->getPdo();
        $this->info('Database: connected (' . DB::getDefaultConnection() . ')');
    } catch (\Throwable $e) {
        $this->error('Database: connection failed — ' . $e->getMessage());
    }

    // Show number of routes (quick sanity)
    try {
        $routeCount = count(app('router')->getRoutes());
        $this->line("Registered routes: {$routeCount}");
    } catch (\Throwable $e) {
        // don't fail command if router unavailable
        $this->line('Registered routes: (could not retrieve) - ' . $e->getMessage());
    }

})->describe('Show simple LMS status (DB connection, env, routes count)');

/*
|---------------------------------------------------------------------------
| Example scheduled command registration (uncomment to use)
|---------------------------------------------------------------------------
|
| If you want a scheduled job, put schedules in app/Console/Kernel.php
| This section is left as a reference; Laravel schedules should be added
| inside the Kernel.
|
| $schedule->command('lms:clear')->daily();
|
*/
