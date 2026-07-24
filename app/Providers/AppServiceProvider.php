<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Services\LanguageHelper;


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
        // Trust all proxies (including ngrok) for HTTPS
        Request::setTrustedProxies(
            ['*'],
            \Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_HOST |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                \Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
        );

        // Set application locale
        $this->setApplicationLocale();
    }

    /**
     * Set application locale based on user preference or location detection
     */
    protected function setApplicationLocale(): void
    {
        // Check for session language first (for guests)
        if (!Auth::check() && session()->has('language')) {
            $locale = session('language');
            if (LanguageHelper::isSupported($locale)) {
                app()->setLocale($locale);
                return;
            }
        }

        // Check authenticated user's preference
        if (Auth::check() && filled(Auth::user()->language)) {
            $locale = Auth::user()->language;
            if (LanguageHelper::isSupported($locale)) {
                app()->setLocale($locale);
                return;
            }
        }

        // Detect language based on user's location (IP)
        // This runs if no preference is set
        $detectedLocale = LanguageHelper::detectLanguageFromIP();
        app()->setLocale($detectedLocale);
    }
}
