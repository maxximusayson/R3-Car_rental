<?php

// EventServiceProvider.php

namespace App\Providers;

use App\Models\AuditTable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        // Listen for login event
        Event::listen(Login::class, function ($event) {
            if ($event->user) {
                AuditTable::create([
                    'action' => 'logged in',
                    'user' => $event->user->id,
                    'details' => 'User logged in successfully.',
                ]);
            }
        });

        // Listen for logout event
        Event::listen(Logout::class, function ($event) {
            if ($event->user) {
                AuditTable::create([
                    'action' => 'logged out',
                    'user' => $event->user->id,
                    'details' => 'User logged out successfully.',
                ]);
            }
        });
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
