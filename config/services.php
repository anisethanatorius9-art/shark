<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'public' => env('STRIPE_PUBLIC_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
        'webhook' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'google_play' => [
        'package_name' => env('GOOGLE_PLAY_PACKAGE_NAME'),
        'service_account_json' => env('GOOGLE_PLAY_SERVICE_ACCOUNT_JSON'),
        'service_account_path' => env('GOOGLE_PLAY_SERVICE_ACCOUNT_PATH'),
        // Google Play verification requires either:
        // 1) GOOGLE_PLAY_SERVICE_ACCOUNT_JSON containing the full JSON credentials,
        // or
        // 2) GOOGLE_PLAY_SERVICE_ACCOUNT_PATH pointing to the JSON key file.
        // Ensure the service account has access to the Android Publisher API.
        'products' => [
            'go' => env('GOOGLE_PLAY_PRODUCT_GO', 'go'),
            'plus' => env('GOOGLE_PLAY_PRODUCT_PLUS', 'plus'),
            'pro' => env('GOOGLE_PLAY_PRODUCT_PRO', 'pro'),
        ],
    ],

    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://localhost:11434'),
        'model' => env('OLLAMA_MODEL', 'llama3.2:1b'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY', ''),
        'model' => env('GROQ_MODEL', 'llama-3.1-70b-versatile'),
    ],

    'duckduckgo' => [
        'enabled' => env('DUCKDUCKGO_ENABLED', true),
    ],

];
