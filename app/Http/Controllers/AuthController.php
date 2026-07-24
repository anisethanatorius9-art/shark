<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update avatar if not set
                if (!$user->avatar && $googleUser->getAvatar()) {
                    // For Google avatars, we'll just store the URL
                    // You could download and store the image locally if needed
                    $user->google_avatar = $googleUser->getAvatar();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(\Illuminate\Support\Str::random(24)),
                    'google_avatar' => $googleUser->getAvatar(),
                    'theme' => 'light',
                    'language' => 'en',
                ]);
            }

            // Log the user in
            Auth::login($user);

            // Redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());

            return redirect()->route('login')
                ->with('error', 'Unable to sign in with Google. Please try again.');
        }
    }
}
