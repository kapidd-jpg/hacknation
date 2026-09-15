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
        if (config('app.vercel')) {
            $this->app->useStoragePath('/tmp/storage');
            config(['view.compiled' => '/tmp/storage/framework/views']);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.vercel')) {
            foreach (['framework/cache/data', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
                $path = storage_path($dir);

                if (! is_dir($path)) {
                    mkdir($path, 0755, true);
                }
            }
        }
    }
}
