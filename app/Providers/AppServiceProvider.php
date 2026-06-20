<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        if (request()->secure() || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Max 60 QR scans per minute per IP
        RateLimiter::for('qr-scan', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        // Max 120 menu views per minute per IP
        RateLimiter::for('public-menu', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        \Illuminate\Support\Facades\Event::listen(function (\Illuminate\Mail\Events\MessageSent $event) {
            $message = $event->message;
            $to = collect($message->getTo())->map(fn($addr) => $addr->getAddress())->implode(', ');
            
            \Illuminate\Support\Facades\Log::channel('emails')->info("Email successfully sent", [
                'subject' => $message->getSubject(),
                'to'      => $to,
            ]);
        });
    }
}
