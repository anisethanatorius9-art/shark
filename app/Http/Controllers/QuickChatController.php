<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle quick chat message - returns answer directly without creating a chat
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'model' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Determine which model to use based on subscription
        $requestedModel = $request->input('model', 'gpt-3.5-turbo');

        // If user doesn't have subscription, force GPT-3.5
        if (!$user->canUseGPT4()) {
            $model = 'gpt-3.5-turbo';
        } else {
            $model = $requestedModel;
        }

        try {
            $aiResponse = $this->aiService->getResponse($request->message, [], $model);

            if (empty($aiResponse)) {
                return response()->json([
                    'success' => false,
                    'error' => 'I\'m having trouble connecting. Please try again.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'response' => $aiResponse,
                'model_used' => $model,
                'is_premium' => $user->canUseGPT4(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Quick Chat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    /**
     * Get available models based on user's subscription
     */
    public function models()
    {
        $user = Auth::user();
        $isPremium = $user->canUseGPT4();

        $allModels = [
            ['id' => 'gpt-4o', 'name' => 'GPT-4o', 'premium' => true],
            ['id' => 'gpt-4-turbo', 'name' => 'GPT-4 Turbo', 'premium' => true],
            ['id' => 'gpt-4', 'name' => 'GPT-4', 'premium' => true],
            ['id' => 'gpt-3.5-turbo', 'name' => 'GPT-3.5 Turbo', 'premium' => false],
            ['id' => 'claude-3-opus', 'name' => 'Claude 3 Opus', 'premium' => true],
            ['id' => 'claude-3-sonnet', 'name' => 'Claude 3 Sonnet', 'premium' => true],
            ['id' => 'llama-3.1-70b', 'name' => 'Llama 3.1 70B', 'premium' => false],
            ['id' => 'mistral-7b', 'name' => 'Mistral 7B', 'premium' => false],
        ];

        // Filter models based on subscription
        $availableModels = array_filter($allModels, function ($model) use ($isPremium) {
            return $isPremium || !$model['premium'];
        });

        return response()->json([
            'models' => array_values($availableModels),
            'is_premium' => $isPremium,
        ]);
    }
}
