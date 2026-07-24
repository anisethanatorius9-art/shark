<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * LanguageHelper - Service for detecting user location and managing language preferences
 *
 * This service helps detect user's country based on IP address and maps it to appropriate language
 */
class LanguageHelper
{
    /**
     * Supported languages in the application
     */
    public const SUPPORTED_LANGUAGES = [
        'en' => 'English',
        'sw' => 'Swahili',
    ];

    /**
     * Country to language mapping
     * Map countries to their preferred language
     */
    public const COUNTRY_TO_LANGUAGE = [
        // East Africa - Swahili speaking countries
        'TZ' => 'sw', // Tanzania
        'KE' => 'sw', // Kenya
        'UG' => 'sw', // Uganda
        'RW' => 'sw', // Rwanda
        'BI' => 'sw', // Burundi
        'MZ' => 'sw', // Mozambique
        'CD' => 'sw', // Democratic Republic of Congo
        'ZM' => 'sw', // Zambia (some regions)

        // Default English speaking countries
        'US' => 'en', // United States
        'GB' => 'en', // United Kingdom
        'CA' => 'en', // Canada
        'AU' => 'en', // Australia
        'NZ' => 'en', // New Zealand
        'IE' => 'en', // Ireland
        'ZA' => 'en', // South Africa
        'NG' => 'en', // Nigeria
        'GH' => 'en', // Ghana
        'LS' => 'en', // Lesotho
        'BW' => 'en', // Botswana
        'ZW' => 'en', // Zimbabwe

        // Other countries - default to English
        'default' => 'en',
    ];

    /**
     * Get the user's language based on their IP address
     *
     * @param string|null $ipAddress Optional custom IP address
     * @return string Language code (en/sw)
     */
    public static function detectLanguageFromIP(?string $ipAddress = null): string
    {
        $ip = $ipAddress ?? self::getClientIP();

        // Skip localhost/private IPs - default to English
        if (self::isLocalhost($ip)) {
            return self::getDefaultLanguage();
        }

        try {
            $countryCode = self::getCountryFromIP($ip);

            if ($countryCode) {
                return self::mapCountryToLanguage($countryCode);
            }
        } catch (\Exception $e) {
            Log::warning('Language detection failed: ' . $e->getMessage());
        }

        return self::getDefaultLanguage();
    }

    /**
     * Get client's IP address from request
     *
     * @return string
     */
    public static function getClientIP(): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_FORWARDED_FOR',     // Proxy
            'HTTP_X_REAL_IP',           // Nginx proxy
            'HTTP_CLIENT_IP',          // Client IP
            'REMOTE_ADDR',              // Default
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                // Handle X-Forwarded-For which can contain multiple IPs
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (self::isValidIP($ip)) {
                    return $ip;
                }
            }
        }

        return '127.0.0.1';
    }

    /**
     * Validate IP address
     *
     * @param string $ip
     * @return bool
     */
    public static function isValidIP(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }

    /**
     * Check if IP is localhost/private
     *
     * @param string $ip
     * @return bool
     */
    public static function isLocalhost(string $ip): bool
    {
        $localhostPatterns = [
            '127.',           // 127.0.0.0/8
            '10.',            // 10.0.0.0/8
            '172.16.',        // 172.16.0.0/12
            '192.168.',       // 192.168.0.0/16
            'localhost',
            '::1',            // IPv6 localhost
        ];

        foreach ($localhostPatterns as $pattern) {
            if (strpos($ip, $pattern) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get country code from IP address using free IP geolocation service
     *
     * @param string $ip
     * @return string|null
     */
    public static function getCountryFromIP(string $ip): ?string
    {
        // Skip private/localhost IPs
        if (self::isLocalhost($ip)) {
            return null;
        }

        // Use cache to avoid repeated API calls
        $cacheKey = "geoip_country:{$ip}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            try {
                // Using ip-api.com free API (100 requests/minute limit)
                // For production, consider using a paid service or local database
                $response = Http::timeout(5)
                    ->get("http://ip-api.com/json/{$ip}?fields=countryCode");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['countryCode'] ?? null;
                }
            } catch (\Exception $e) {
                Log::warning('IP geolocation lookup failed: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Map country code to language
     *
     * @param string $countryCode
     * @return string
     */
    public static function mapCountryToLanguage(string $countryCode): string
    {
        return self::COUNTRY_TO_LANGUAGE[$countryCode] ?? self::COUNTRY_TO_LANGUAGE['default'];
    }

    /**
     * Get default language based on application config
     *
     * @return string
     */
    public static function getDefaultLanguage(): string
    {
        return config('app.fallback_locale', 'en');
    }

    /**
     * Get all supported languages
     *
     * @return array
     */
    public static function getSupportedLanguages(): array
    {
        return self::SUPPORTED_LANGUAGES;
    }

    /**
     * Check if a language is supported
     *
     * @param string $language
     * @return bool
     */
    public static function isSupported(string $language): bool
    {
        return array_key_exists($language, self::SUPPORTED_LANGUAGES);
    }

    /**
     * Get language name by code
     *
     * @param string $code
     * @return string
     */
    public static function getLanguageName(string $code): string
    {
        return self::SUPPORTED_LANGUAGES[$code] ?? $code;
    }

    /**
     * Get flag emoji for language
     *
     * @param string $language
     * @return string
     */
    public static function getLanguageFlag(string $language): string
    {
        $flags = [
            'en' => '🇺🇸',
            'sw' => '🇹🇿',
        ];

        return $flags[$language] ?? '🌐';
    }

    /**
     * Get available languages with their metadata
     *
     * @return array
     */
    public static function getLanguagesWithMeta(): array
    {
        $languages = [];

        foreach (self::SUPPORTED_LANGUAGES as $code => $name) {
            $languages[] = [
                'code' => $code,
                'name' => $name,
                'flag' => self::getLanguageFlag($code),
                'native_name' => self::getNativeName($code),
            ];
        }

        return $languages;
    }

    /**
     * Get native name of language
     *
     * @param string $code
     * @return string
     */
    public static function getNativeName(string $code): string
    {
        $nativeNames = [
            'en' => 'English',
            'sw' => 'Kiswahili',
        ];

        return $nativeNames[$code] ?? $code;
    }

    /**
     * Detect language and set it in the application
     * This is a convenience method that detects and applies the language
     *
     * @param string|null $userPreference User's saved preference
     * @return string The detected/applied language code
     */
    public static function detectAndSetLanguage(?string $userPreference = null): string
    {
        // If user has a preference, use it
        if (!empty($userPreference) && self::isSupported($userPreference)) {
            return $userPreference;
        }

        // Otherwise, detect from IP
        return self::detectLanguageFromIP();
    }

    /**
     * Get detected country info for display
     *
     * @return array|null
     */
    public static function getDetectedCountry(): ?array
    {
        $ip = self::getClientIP();

        if (self::isLocalhost($ip)) {
            return [
                'country' => 'Local',
                'country_code' => 'XX',
                'language' => self::getDefaultLanguage(),
                'is_localhost' => true,
            ];
        }

        $countryCode = self::getCountryFromIP($ip);

        if ($countryCode) {
            return [
                'country' => self::getCountryName($countryCode),
                'country_code' => $countryCode,
                'language' => self::mapCountryToLanguage($countryCode),
                'is_localhost' => false,
            ];
        }

        return null;
    }

    /**
     * Get country name from code
     *
     * @param string $code
     * @return string
     */
    public static function getCountryName(string $code): string
    {
        $countries = [
            'TZ' => 'Tanzania',
            'KE' => 'Kenya',
            'UG' => 'Uganda',
            'RW' => 'Rwanda',
            'BI' => 'Burundi',
            'MZ' => 'Mozambique',
            'CD' => 'DR Congo',
            'ZM' => 'Zambia',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'NZ' => 'New Zealand',
            'IE' => 'Ireland',
            'ZA' => 'South Africa',
            'NG' => 'Nigeria',
            'GH' => 'Ghana',
            'LS' => 'Lesotho',
            'BW' => 'Botswana',
            'ZW' => 'Zimbabwe',
        ];

        return $countries[$code] ?? $code;
    }
}
