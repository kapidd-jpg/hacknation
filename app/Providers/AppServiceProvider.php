<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('VERCEL')) {
            $this->app->useStoragePath('/tmp/storage');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('VERCEL')) {
            foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
                $path = storage_path($dir);

                if (! is_dir($path)) {
                    mkdir($path, 0755, true);
                }
            }
        }
    }
}
