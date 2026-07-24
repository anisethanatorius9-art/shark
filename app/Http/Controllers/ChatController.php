<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{

    public function create()
    {
        return view('chats.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $chat = $user->chats()->create([
            'title' => $request->title,
            'uuid' => Str::uuid(),
        ]);

        return redirect()->route('chats.show', $chat);
    }


    public function show($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();

        if ($chat->user_id !== Auth::id()) {
            return redirect()->route('chats.create')->with('error', 'You are not authorized to view this chat.');
        }

        $chat->load('messages');

        return view('chats.show', compact('chat'));
    }


    public function share($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();

        return view('chats.share', compact('chat'));
    }


    public function destroy($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();
        if ($chat->user_id !== Auth::id()) abort(403);

        $chat->delete();

        return redirect()->route('chats.create')->with('success', 'Chat deleted successfully.');
    }

    public function destroyAll()
    {
        /** @var User $user */
        $user = Auth::user();

        // Get all user's chats
        $chats = $user->chats()->get();

        // Delete all messages for each chat first
        foreach ($chats as $chat) {
            $chat->messages()->delete();
        }

        // Delete all chats for the current user
        $user->chats()->delete();

        return redirect()->back()->with('success', 'All chats deleted successfully.');
    }


    public function rename($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();
        if ($chat->user_id !== Auth::id()) abort(403);

        return view('chats.rename', compact('chat'));
    }

    public function pin($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();
        if ($chat->user_id !== Auth::id()) abort(403);

        $chat->update(['pinned' => true]);

        return back();
    }

    public function archive($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();
        if ($chat->user_id !== Auth::id()) abort(403);

        $chat->update(['archived' => true]);

        return back();
    }

    public function group($uuid)
    {
        $chat = Chat::where('uuid', $uuid)->firstOrFail();
        if ($chat->user_id !== Auth::id()) abort(403);

        return view('chats.group', compact('chat'));
    }

    public function apiSearch(Request $request)
    {
        $q = $request->query('q');
        $results = [];

        if ($q && strlen($q) >= 2) {
            $results = Chat::where('user_id', Auth::id())
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhereHas('messages', function ($mq) use ($q) {
                            $mq->where('content', 'like', "%{$q}%");
                        });
                })
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($chat) => [
                    'id' => $chat->uuid,
                    'title' => $chat->title,
                    'url' => route('chats.show', $chat->uuid),
                ])
                ->toArray();
        }

        return response()->json(['results' => $results]);
    }

    public function createGroup()
    {
        return view('chats.group-create'); // make this blade later
    }
}
