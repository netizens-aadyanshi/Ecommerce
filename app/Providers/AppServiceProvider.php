<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// --- ADD THESE THREE LINES ---
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Verified;
use App\Jobs\SendWelcomeEmail;
// -----------------------------

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Now PHP knows exactly what 'Event', 'Verified', and 'SendWelcomeEmail' are
        Event::listen(Verified::class, function ($event) {
            SendWelcomeEmail::dispatch($event->user);
        });
    }
}
