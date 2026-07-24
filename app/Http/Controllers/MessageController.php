<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function store(Request $request, $uuid)
    {
        // Increase timeout for AI requests (5 minutes)
        set_time_limit(300);

        $request->validate([
            'content' => 'required|string',
        ]);

        $chat = Chat::where('uuid', $uuid)->firstOrFail();

        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        // Save user's message
        $userMessage = $chat->messages()->create([
            'content' => $request->content,
            'role' => 'user',
        ]);

        // Get conversation history for context
        $history = $chat->messages()
            ->where('id', '!=', $userMessage->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($msg) => [
                'role' => $msg->role ?? 'user',
                'content' => $msg->content
            ])
            ->toArray();

        // Get the selected model (default to GPT-4o)
        $model = $request->input('model', 'gpt-4o');

        // Get AI response with error handling
        try {
            \Log::info('Calling AI Service with message', ['message' => substr($request->content, 0, 100)]);
            $aiResponse = $this->aiService->getResponse($request->content, $history, $model);

            \Log::info('AI Service returned response', ['response_length' => strlen($aiResponse), 'response_preview' => substr($aiResponse, 0, 100)]);

            // Ensure we always have a string response
            if (empty($aiResponse)) {
                $aiResponse = "I'm having trouble connecting to my knowledge base right now. Please try again in a moment.";
            }
        } catch (\Exception $e) {
            \Log::error('AI Service error: ' . $e->getMessage());
            $aiResponse = "Sorry, I encountered an error while processing your request. Please try again.";
        }

        // Save AI's response
        $chat->messages()->create([
            'content' => $aiResponse,
            'role' => 'assistant',
        ]);

        // Check if request wants JSON (AJAX)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'ai_response' => $aiResponse,
            ]);
        }

        return redirect()->route('chats.show', $chat->uuid);
    }
}
