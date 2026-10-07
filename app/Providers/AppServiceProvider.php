<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // APP_URL is the source of truth for absolute URLs (canonical link,
        // Open Graph, JSON-LD, the sitemap, media and signed links). When the
        // site sits behind a TLS terminator Laravel can still see plain http,
        // which would emit mixed-content URLs, so force the scheme to match.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
