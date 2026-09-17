<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * Catatan: listener verifikasi email tidak didaftarkan karena model User
     * TIDAK mengimplementasikan MustVerifyEmail — daftar verifikasi makanya
     * silent no-op. Bila verifikasi email diaktifkan, daftarkan ulang
     * SendEmailVerificationNotification DI SINI bersamaan dengan menerapkan
     * MustVerifyEmail di model User.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
