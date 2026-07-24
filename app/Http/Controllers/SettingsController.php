<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;


class SettingsController extends Controller
{
    public function edit()
    {
        return view('settings.edit');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $request->user()->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Name updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars');

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Profile photo updated.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password']);
        }

        $user = $request->user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Account deleted.');
    }

    public function boot()
    {
        if (auth()->check()) {
            App::setLocale(auth()->user()->language);
        }
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,sw',
        ]);

        $request->user()->update([
            'language' => $request->language,
        ]);

        app()->setLocale($request->language);

        return back()->with('success', 'Language updated.');
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $request->user()->update([
            'theme' => $request->theme, // make sure column exists
        ]);

        return back()->with('success', 'Theme updated.');
    }
}
