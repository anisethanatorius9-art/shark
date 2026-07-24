<?php

namespace App\Http\Controllers;

use App\Services\LanguageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Change application language
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:en,sw',
        ]);

        $language = $request->input('language');

        // Update user's language preference if authenticated
        if (Auth::check()) {
            try {
                Auth::user()->update(['language' => $language]);
            } catch (\Exception $e) {
                Log::error('Failed to update user language: ' . $e->getMessage());
            }
        }

        // Set the application locale
        app()->setLocale($language);

        // Store in session for guests
        session(['language' => $language]);

        // Get the referring page or default to dashboard
        $redirectTo = $request->query('redirect', route('dashboard'));

        return redirect($redirectTo)->with('status', 'Language changed to ' . LanguageHelper::getLanguageName($language));
    }

    /**
     * Detect and apply language based on user location
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detectLanguage(Request $request)
    {
        // Check if user has a preference
        $userPreference = null;

        if (Auth::check()) {
            $userPreference = Auth::user()->language;
        } else {
            // Check session for guests
            $userPreference = session('language');
        }

        // Detect language from IP
        $detectedLanguage = LanguageHelper::detectLanguageFromIP();

        // If user has no preference, apply detected language
        if (empty($userPreference)) {
            app()->setLocale($detectedLanguage);

            return response()->json([
                'detected' => true,
                'language' => $detectedLanguage,
                'language_name' => LanguageHelper::getLanguageName($detectedLanguage),
                'language_flag' => LanguageHelper::getLanguageFlag($detectedLanguage),
                'message' => 'Language detected based on your location',
            ]);
        }

        // User has preference, use it
        app()->setLocale($userPreference);

        return response()->json([
            'detected' => false,
            'language' => $userPreference,
            'language_name' => LanguageHelper::getLanguageName($userPreference),
            'language_flag' => LanguageHelper::getLanguageFlag($userPreference),
            'message' => 'Using your saved language preference',
        ]);
    }

    /**
     * Get current language info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentLanguage()
    {
        $currentLocale = app()->getLocale();

        // Get country detection info
        $countryInfo = LanguageHelper::getDetectedCountry();

        return response()->json([
            'current_language' => $currentLocale,
            'language_name' => LanguageHelper::getLanguageName($currentLocale),
            'language_flag' => LanguageHelper::getLanguageFlag($currentLocale),
            'available_languages' => LanguageHelper::getLanguagesWithMeta(),
            'detected_country' => $countryInfo,
            'is_authenticated' => Auth::check(),
        ]);
    }

    /**
     * Get supported languages list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSupportedLanguages()
    {
        return response()->json([
            'languages' => LanguageHelper::getLanguagesWithMeta(),
        ]);
    }

    /**
     * Get translations for a language
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTranslations(Request $request)
    {
        $language = $request->query('lang', app()->getLocale());

        // Load translations
        $translations = [];
        if (file_exists(lang_path($language))) {
            $files = glob(lang_path($language . '/*.php'));
            foreach ($files as $file) {
                $key = basename($file, '.php');
                $translations[$key] = include $file;
            }
        }

        return response()->json($translations);
    }

    /**
     * Set language via AJAX (for dropdown selection)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLanguageAjax(Request $request)
    {
        $request->validate([
            'language' => 'required|string|in:en,sw',
        ]);

        $language = $request->input('language');

        // Update user's language preference if authenticated
        if (Auth::check()) {
            try {
                Auth::user()->update(['language' => $language]);
            } catch (\Exception $e) {
                Log::error('Failed to update user language: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save language preference',
                ], 500);
            }
        }

        // Set the application locale
        app()->setLocale($language);

        // Store in session for guests
        session(['language' => $language]);

        // Load translations for the new language
        $translations = [];
        if (file_exists(lang_path($language))) {
            $files = glob(lang_path($language . '/*.php'));
            foreach ($files as $file) {
                $key = basename($file, '.php');
                $translations = array_merge($translations, include $file);
            }
        }

        return response()->json([
            'success' => true,
            'language' => $language,
            'language_name' => LanguageHelper::getLanguageName($language),
            'language_flag' => LanguageHelper::getLanguageFlag($language),
            'translations' => $translations,
            'message' => 'Language changed successfully',
        ]);
    }
}
