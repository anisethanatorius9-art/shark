<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuickChatController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChatFeatureController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language Routes
Route::post('/language/change', [LanguageController::class, 'changeLanguage'])->name('language.change');
Route::get('/language/detect', [LanguageController::class, 'detectLanguage'])->name('language.detect');
Route::get('/language/current', [LanguageController::class, 'getCurrentLanguage'])->name('language.current');
Route::get('/language/supported', [LanguageController::class, 'getSupportedLanguages'])->name('language.supported');
Route::get('/language/translations', [LanguageController::class, 'getTranslations'])->name('language.translations');
Route::post('/language/set-ajax', [LanguageController::class, 'setLanguageAjax'])->name('language.set-ajax');

// Google OAuth Routes
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', \App\Livewire\DashboardComponent::class)->name('dashboard');

    // Projects
    Route::get('/projects', \App\Livewire\ProjectIndexComponent::class)->name('projects.index');
    Route::get('/projects/create', \App\Livewire\ProjectCreateComponent::class)->name('projects.create');
    Route::get('/projects/{project}', \App\Livewire\ProjectShowComponent::class)->name('projects.show');

    // Orders
    Route::get('/orders', \App\Livewire\OrderTableComponent::class)->name('orders.index');

    Route::get('/chats/create', \App\Livewire\ChatCreateComponent::class)->name('chats.create');
    Route::get('/chats/{uuid}', \App\Livewire\ChatShowComponent::class)->name('chats.show');
    Route::post('/chats/{uuid}/messages', [MessageController::class, 'store'])->name('messages.store');

    // Delete all chats
    Route::delete('/chats/all', [ChatController::class, 'destroyAll'])->name('chats.delete.all');

    // Quick chat - instant AI answers without creating a chat
    Route::post('/quick-chat', [QuickChatController::class, 'chat'])->name('quick.chat');
    Route::get('/quick-chat/models', [QuickChatController::class, 'models'])->name('quick.chat.models');

    Route::get('/api/chats/search', [ChatController::class, 'apiSearch'])->name('chats.api.search');

    // Quick chat features: file upload, action toggles (thinking/deep), and simple web search
    Route::post('/chat/upload', [ChatFeatureController::class, 'upload'])->name('chat.upload');
    Route::post('/chat/action', [ChatFeatureController::class, 'action'])->name('chat.action');
    Route::get('/chat/web-search', [ChatFeatureController::class, 'webSearch'])->name('chat.websearch');

    Route::get('/group-chats/create', [ChatController::class, 'createGroup'])
        ->name('chats.group.create');

    // Message Streaming Settings
    Route::get('/chats/streaming/settings', \App\Livewire\StreamingSettingsComponent::class)->name('chats.streaming.settings');

    Route::get('/chats/{uuid}/rename', [ChatController::class, 'rename'])->name('chats.rename');
    Route::get('/chats/{uuid}/group', [ChatController::class, 'group'])->name('chats.group');
    Route::post('/chats/{uuid}/pin', [ChatController::class, 'pin'])->name('chats.pin');
    Route::post('/chats/{uuid}/archive', [ChatController::class, 'archive'])->name('chats.archive');
    Route::delete('/chats/{uuid}', [ChatController::class, 'destroy'])->name('chats.destroy');

    Route::get('/chats/public/{uuid}', [ChatController::class, 'publicView'])
        ->name('chats.public');


    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/apps', [AppController::class, 'index'])->name('apps.index');


    Route::redirect('/settings', '/settings/profile')->name('settings');

    // Profile update (photo + name)
    Route::post('/settings/profile/update', function (Request $request) {
        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated.');
    })->name('profile.update');

    // Appearance (theme)
    Route::post('/settings/appearance', function (Request $request) {
        $user = Auth::user();
        $theme = $request->input('theme', 'light');
        $user->theme = $theme;
        $user->save();
        return redirect()->back()->with('success', 'Appearance saved.');
    })->name('appearance.update');

    // Language selection
    Route::post('/settings/language', function (Request $request) {
        $user = Auth::user();
        $lang = $request->input('language', 'en');
        $user->language = $lang;
        $user->save();
        // Set locale for current session
        app()->setLocale($lang);
        return redirect()->back()->with('success', 'Language saved.');
    })->name('language.edit');

    // Delete account
    Route::delete('/settings/account', function (Request $request) {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Account deleted');
    })->name('account.delete');

    Route::get('/subscription/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');
    Route::get('/subscription/checkout/{plan}', \App\Livewire\CheckoutComponent::class)->name('subscription.checkout');
    Route::post('/subscription/process-payment', [SubscriptionController::class, 'processPayment'])->name('subscription.process-payment');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');


    Route::get('/settings/profile', \App\Livewire\Settings\ProfileComponent::class)->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/language', 'settings.language')->name('settings.language');
    Volt::route('settings/delete-account', 'settings.delete-account')->name('settings.delete-account');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('settings.two-factor');
});
