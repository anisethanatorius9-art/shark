<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and has language preference
        if (Auth::check() && Auth::user()->language) {
            app()->setLocale(Auth::user()->language);
        } elseif (session()->has('language')) {
            app()->setLocale(session('language'));
        }

        return $next($request);
    }
}