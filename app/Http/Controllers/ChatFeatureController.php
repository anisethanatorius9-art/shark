<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ChatFeatureController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
            'path' => $path,
        ], 201);
    }

    public function action(Request $request)
    {
        $action = $request->input('action');
        $allowed = ['thinking', 'deep_chat', 'create'];
        if (!in_array($action, $allowed)) {
            return response()->json(['error' => 'Invalid action'], 422);
        }

        
        session(['chat_action_' . $action => true]);

        return response()->json(['status' => 'ok', 'action' => $action]);
    }

    public function webSearch(Request $request)
    {
        $q = $request->query('q');
        if (!$q) {
            return response()->json(['results' => []]);
        }

        // Use DuckDuckGo Instant Answer as a free fallback for simple search results
        $resp = Http::withHeaders(['Accept' => 'application/json'])
            ->get('https://api.duckduckgo.com/', [
                'q' => $q,
                'format' => 'json',
                'no_html' => 1,
                'skip_disambig' => 1,
            ]);

        $results = [];
        if ($resp->successful()) {
            $data = $resp->json();
            if (!empty($data['RelatedTopics'])) {
                foreach ($data['RelatedTopics'] as $topic) {
                    if (isset($topic['Text'])) {
                        $results[] = [
                            'title' => $topic['Text'],
                            'url' => $topic['FirstURL'] ?? '',
                            'snippet' => $topic['Text'],
                        ];
                    } elseif (!empty($topic['Topics'])) {
                        foreach ($topic['Topics'] as $t) {
                            $results[] = [
                                'title' => $t['Text'] ?? '',
                                'url' => $t['FirstURL'] ?? '',
                                'snippet' => $t['Text'] ?? '',
                            ];
                        }
                    }
                }
            }

            if (empty($results) && !empty($data['AbstractText'])) {
                $results[] = [
                    'title' => $data['Heading'] ?? $q,
                    'url' => '',
                    'snippet' => $data['AbstractText'],
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
