<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $apps = [
            [
                'id' => 'sharkgpt',
                'name' => 'SharkGPT',
                'description' => 'AI assistant for code, chats and project help.',
                'url' => route('apps.index') . '#sharkgpt',
                'icon' => '🤖',
            ],
            [
                'id' => 'chrome',
                'name' => 'Chrome',
                'description' => 'Browse the web with Chrome browser.',
                'url' => 'https://www.google.com/chrome/',
                'icon' => '🌐',
            ],
            [
                'id' => 'google',
                'name' => 'Google',
                'description' => 'Search the web, access Gmail, Drive and more.',
                'url' => 'https://www.google.com/',
                'icon' => '🔍',
            ],
            [
                'id' => 'spotify',
                'name' => 'Spotify',
                'description' => 'Stream millions of songs and podcasts.',
                'url' => 'https://open.spotify.com/',
                'icon' => '🎵',
            ],
            [
                'id' => 'phonex',
                'name' => 'PhoneX',
                'description' => 'Video calls and messaging app.',
                'url' => 'https://phonex.app/',
                'icon' => '📱',
            ],
        ];

        return view('apps.index', compact('apps'));
    }
}
