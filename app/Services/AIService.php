<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use OpenRouter\Client;

class AIService
{
    /**
     * Available AI models
     */
    const MODELS = [
        'gpt-4o' => 'GPT-4o',
        'gpt-4-turbo' => 'GPT-4 Turbo',
        'gpt-4' => 'GPT-4',
        'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
        'claude-3-opus' => 'Claude 3 Opus',
        'claude-3-sonnet' => 'Claude 3 Sonnet',
        'llama-3.1-70b' => 'Llama 3.1 70B',
        'mistral-7b' => 'Mistral 7B',
    ];

    /**
     * Default model
     */
    const DEFAULT_MODEL = 'gpt-4o';

    /**
     * Process user message and return AI response
     */
    public function getResponse(string $message, array $conversationHistory = [], ?string $model = null): string
    {
        $model = $model ?? config('services.ai.default_model', self::DEFAULT_MODEL);

        \Log::info('AI Request started', ['model' => $model, 'message_length' => strlen($message)]);

        // Try OpenRouter with specified model first (supports GPT-4 and many others)
        \Log::info('Attempting OpenRouter API...');
        $openRouterResponse = $this->getOpenRouterResponse($message, $conversationHistory, $model);

        if ($openRouterResponse) {
            \Log::info('OpenRouter API succeeded');
            return $openRouterResponse;
        }

        \Log::info('OpenRouter API failed, trying Groq...');

        // Fallback to default providers if specific model fails
        $groqResponse = $this->getGroqResponse($message, $conversationHistory);

        if ($groqResponse) {
            return $groqResponse;
        }

        // Then, try to get an instant answer from DuckDuckGo
        \Log::info('Trying DuckDuckGo instant answer...');
        $instantAnswer = $this->getInstantAnswer($message);

        if ($instantAnswer) {
            return $instantAnswer;
        }

        // Try Ollama if configured
        \Log::info('Trying Ollama...');
        $ollamaResponse = $this->getOllamaResponse($message, $conversationHistory);

        if ($ollamaResponse) {
            return $ollamaResponse;
        }

        // Try free online AI API as fallback
        \Log::info('Trying online AI fallback...');
        $onlineAIResponse = $this->getOnlineAIResponse($message, $conversationHistory);

        if ($onlineAIResponse) {
            return $onlineAIResponse;
        }

        // Fallback response
        \Log::info('All AI providers failed, returning fallback response');
        return $this->getFallbackResponse($message);
    }

    /**
     * Get response from Groq (free AI API)
     */
    private function getGroqResponse(string $message, array $history): ?string
    {
        $apiKey = config('services.groq.api_key');

        if (empty($apiKey)) {
            return null;
        }

        try {
            $model = config('services.groq.model', 'llama-3.1-70b-versatile');

            // Build conversation context
            $messages = [];

            // Add system message - IMPROVED for general purpose
            $messages[] = [
                'role' => 'system',
                'content' => 'You are Shark AI, a helpful, friendly, and knowledgeable AI assistant. Always provide accurate, factual, and correct answers to any questions. Double-check information for accuracy. You can help with: answering questions on any topic, writing and explaining code in any programming language, solving problems, having conversations, providing explanations, and much more. Be concise but thorough. Provide accurate and helpful responses.'
            ];

            // Add conversation history
            foreach ($history as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? ''
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'max_tokens' => 4096,
                    'temperature' => 0.3
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error('Groq API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get response from OpenRouter (supports GPT-4, Claude, Llama, etc.)
     */
    private function getOpenRouterResponse(string $message, array $history, ?string $model = null): ?string
    {
        $apiKey = env('OPENROUTER_API_KEY');

        \Log::info('OpenRouter check', ['has_api_key' => !empty($apiKey), 'key_length' => $apiKey ? strlen($apiKey) : 0]);

        if (empty($apiKey)) {
            \Log::warning('OpenRouter API key is empty');
            return null;
        }

        try {
            // Map model aliases to OpenRouter model IDs
            $modelMap = [
                'gpt-4o' => 'openai/gpt-4o',
                'gpt-4-turbo' => 'openai/gpt-4-turbo',
                'gpt-4' => 'openai/gpt-4',
                'gpt-3.5-turbo' => 'openai/gpt-3.5-turbo',
                'claude-3-opus' => 'anthropic/claude-3-opus',
                'claude-3-sonnet' => 'anthropic/claude-3-sonnet',
                'llama-3.1-70b' => 'meta-llama/llama-3.1-70b-instruct',
                'mistral-7b' => 'mistralai/mistral-7b-instruct',
            ];

            $routerModel = $modelMap[$model] ?? $model ?? 'mistralai/mistral-7b-instruct';

            // Build conversation context
            $messages = [];

            // Add system message - IMPROVED for general purpose conversation
            $messages[] = [
                'role' => 'system',
                'content' => 'You are Shark AI, a helpful, friendly, and knowledgeable AI assistant. Always provide accurate, factual, and correct answers to any questions. Double-check information for accuracy. You can help with: answering questions on any topic, writing and explaining code in any programming language, solving problems, having conversations, providing explanations, and much more. Be concise but thorough. Provide accurate and helpful responses. When users ask for code, provide complete working examples with proper syntax. When users ask about graphs/charts, respond with code in [PYTHON] tags using matplotlib/seaborn/plotly. Also support [CHART] tags for JSON: {"type": "bar|line|pie|doughnut", "title": "Title", "labels": ["A"], "data": [10]}. Provide explanation before code.'
            ];

            // Add conversation history
            foreach ($history as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? ''
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            // Use OpenRouter API with proper routing
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => 'https://sharkgpt.com',
                    'X-Title' => 'Shark AI'
                ])
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $routerModel,
                    'messages' => $messages,
                    'max_tokens' => 4096,
                    'temperature' => 0.3
                ]);

            if ($response->successful()) {
                $data = $response->json();
                \Log::info('OpenRouter response received', ['data_keys' => array_keys($data)]);
                return $data['choices'][0]['message']['content'] ?? null;
            }

            // Log error for debugging
            \Log::error('OpenRouter API error: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('OpenRouter API exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get response from OpenAI API directly (for GPT-4)
     */
    private function getOpenAIResponse(string $message, array $history, string $model = 'gpt-4o'): ?string
    {
        $apiKey = env('OPENAI_API_KEY');

        if (empty($apiKey)) {
            return null;
        }

        try {
            // Map model aliases to OpenAI model IDs
            $modelMap = [
                'gpt-4o' => 'gpt-4o',
                'gpt-4-turbo' => 'gpt-4-turbo',
                'gpt-4' => 'gpt-4',
                'gpt-3.5-turbo' => 'gpt-3.5-turbo',
            ];

            $openAIModel = $modelMap[$model] ?? 'gpt-4o';

            // Build conversation context
            $messages = [];

            // Add system message
            $messages[] = [
                'role' => 'system',
                'content' => 'You are Shark AI, a helpful, friendly, and knowledgeable AI assistant. Always provide accurate, factual, and correct answers to any questions. Double-check information for accuracy. When users ask for code in any programming language (Python, JavaScript, PHP, Java, C++, Ruby, Go, Rust, TypeScript, SQL, etc.), provide complete, working code examples with proper syntax. When users ask for graphs/charts using Python, respond with code in [PYTHON] tags using matplotlib/seaborn/plotly. Also support [CHART] tags for JSON: {"type": "bar|line|pie|doughnut", "title": "Title", "labels": ["A"], "data": [10]}. Provide explanation before code.'
            ];

            // Add conversation history
            foreach ($history as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? ''
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            // Use OpenAI API directly
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json'
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $openAIModel,
                    'messages' => $messages,
                    'max_tokens' => 2048,
                    'temperature' => 0.7
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            // Log error for debugging
            \Log::error('OpenAI API error: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('OpenAI API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get response from free online AI API
     */
    private function getOnlineAIResponse(string $message, array $history): ?string
    {
        // Try Wikipedia as a fallback for factual questions
        $wikipediaAnswer = $this->getWikipediaAnswer($message);
        if ($wikipediaAnswer) {
            return $wikipediaAnswer;
        }

        // Try using a free AI API
        try {
            // Use Cohere's free API (has free tier)
            $response = Http::timeout(15)
                ->post('https://api.cohere.ai/v1/chat', [
                    'model' => 'command-r',
                    'message' => $message,
                    'max_tokens' => 300
                ], [
                    'Authorization' => 'Bearer ' . env('COHERE_API_KEY', ''),
                    'Content-Type' => 'application/json'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['text'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error('Online AI API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get answer from Wikipedia
     */
    private function getWikipediaAnswer(string $query): ?string
    {
        try {
            // Clean up the query for Wikipedia
            $searchQuery = trim($query);
            $searchQuery = preg_replace('/^(what is|who is|explain|define|tell me about)\s+/i', '', $searchQuery);

            $response = Http::timeout(10)
                ->get('https://en.wikipedia.org/w/api.php', [
                    'action' => 'query',
                    'list' => 'search',
                    'srsearch' => $searchQuery,
                    'format' => 'json',
                    'srlimit' => 1
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data['query']['search'])) {
                    $pageId = $data['query']['search'][0]['pageid'];

                    // Get the page content
                    $contentResponse = Http::timeout(10)
                        ->get('https://en.wikipedia.org/w/api.php', [
                            'action' => 'query',
                            'pageids' => $pageId,
                            'prop' => 'extracts',
                            'exintro' => true,
                            'explaintext' => true,
                            'format' => 'json'
                        ]);

                    if ($contentResponse->successful()) {
                        $contentData = $contentResponse->json();
                        $extract = $contentData['query']['pages'][$pageId]['extract'] ?? null;

                        if ($extract) {
                            return $extract . "\n\n Source: Wikipedia";
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Wikipedia API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get instant answer from DuckDuckGo API
     */
    private function getInstantAnswer(string $query): ?string
    {
        \Log::info('DuckDuckGo API call starting...');
        try {
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->timeout(10)
                ->get('https://api.duckduckgo.com/', [
                    'q' => $query,
                    'format' => 'json',
                    'no_html' => 1,
                    'skip_disambig' => 1,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check for Abstract answer (most relevant)
                if (!empty($data['AbstractText'])) {
                    $answer = $data['AbstractText'];

                    // Add source if available
                    if (!empty($data['AbstractSource'])) {
                        $answer .= "\n\n Source: " . $data['AbstractSource'];
                    }

                    return $answer;
                }

                // Check for Answer
                if (!empty($data['Answer'])) {
                    return $data['Answer'];
                }

                // Check related topics for simple answers
                if (!empty($data['RelatedTopics'])) {
                    foreach ($data['RelatedTopics'] as $topic) {
                        if (isset($topic['Text']) && isset($topic['FirstURL'])) {
                            // Skip if it's just a link
                            $text = $topic['Text'];
                            if (strlen($text) > 20 && !str_contains($text, ' - ')) {
                                return $text;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('DuckDuckGo API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get response from Ollama local LLM
     */
    private function getOllamaResponse(string $message, array $history): ?string
    {
        $ollamaUrl = config('services.ollama.url', 'http://localhost:11434');

        try {
            // Build conversation context
            $messages = [];

            // Add system prompt
            $messages[] = [
                'role' => 'system',
                'content' => 'You are a helpful and accurate AI programming . When users ask for code in any programming language (Python, JavaScript, PHP, Java, C++, Ruby, Go, Rust, TypeScript, SQL, etc.), provide complete, working code examples with proper syntax. When users ask for graphs/charts using Python, respond with code in [PYTHON] tags using matplotlib/seaborn/plotly. Also support [CHART] tags for JSON: {"type": "bar|line|pie|doughnut", "title": "Title", "labels": ["A"], "data": [10]}. Provide explanation before code.'
            ];

            // Add conversation history
            foreach ($history as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? ''
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            $response = Http::timeout(60)
                ->post("{$ollamaUrl}/api/chat", [
                    'model' => config('services.ollama.model', 'llama3.2'),
                    'messages' => $messages,
                    'stream' => false,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['message']['content'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error('Ollama API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Handle math calculations
     */
    private function handleMath(string $message): ?string
    {
        // Check if it looks like a math problem
        if (preg_match('/^[\d\s\+\-\*\/\(\)\.\,\%\^]+$/', trim($message))) {
            try {
                // Use PHP's eval safely for basic math (only allow numbers and operators)
                $expression = preg_replace('/[^0-9+\-*\/\(\)\.\%\^]/', '', $message);

                if (!empty($expression) && preg_match('/^[\d\+\-\*\/\(\)\.\%\^]+$/', $expression)) {
                    $result = @eval("return {$expression};");

                    if (is_numeric($result)) {
                        return " **Answer:** " . number_format($result, 4, '.', '');
                    }
                }
            } catch (\Exception $e) {
                // Not a valid expression
            }
        }

        // Check for specific math keywords
        $mathKeywords = ['calculate', 'solve', 'what is', 'compute', 'evaluate', 'equals'];
        $hasMathKeyword = false;

        foreach ($mathKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $hasMathKeyword = true;
                break;
            }
        }

        if ($hasMathKeyword) {
            // Extract potential math expression
            $numbers = preg_match_all('/[\d\.]+/', $message, $matches);
            if ($numbers && count($matches[0]) >= 2) {
                // Try basic operations
                $expression = implode('+', $matches[0]);
                try {
                    $result = @eval("return {$expression};");
                    if (is_numeric($result)) {
                        return " **Calculation:** " . $expression . " = " . number_format($result, 2, '.', '');
                    }
                } catch (\Exception $e) {
                    // Ignore
                }
            }
        }

        return null;
    }

    /**
     * Get fallback response when no AI is available
     */
    private function getFallbackResponse(string $message): string
    {
        \Log::info('Generating fallback response for message: ' . substr($message, 0, 50));
        $message = trim($message);
        $messageLower = strtolower($message);

        // Check for math first
        $mathResponse = $this->handleMath($message);
        if ($mathResponse) {
            return $mathResponse;
        }

        // Check if it's a greeting
        $greetings = ['hello', 'hi', 'hey', 'how are you', 'what\'s up', 'habari', 'mambo', 'good morning', 'good evening', 'good afternoon'];
        foreach ($greetings as $greeting) {
            if (stripos($message, $greeting) !== false) {
                return "Hello there! \n\nI'm your SHARK. I'm ready to help you with:\n\n• Answering questions on any topic\n• Writing and explaining code\n• Solving problems\n• Having conversations\n\nWhat would you like to know or discuss today?";
            }
        }

        // Check for thank you
        if (stripos($message, 'thank') !== false || stripos($message, 'thanks') !== false) {
            return "You're welcome! \n\nI'm happy to help. Is there anything else you'd like to ask or discuss?";
        }

        // Check for help request
        if (stripos($message, 'help') !== false) {
            return "I'm here to help! I can assist you with:\n\n **Writing**\n• Code in any programming language\n• Essays, stories, emails\n• Summaries and explanations\n\n **Learning**\n• Explain complex concepts\n• Answer 'what is' questions\n• Math and science problems\n\n **Conversation**\n• Discuss topics\n• Brainstorm ideas\n• Answer questions\n\nJust ask me anything!";
        }

        // Check for who are you / what are you
        if (stripos($message, 'who are you') !== false || stripos($message, 'what are you') !== false) {
            return "I'm Shark AI, a King of the water am here to help you with various tasks.\n\nI can:\n• Answer questions on many topics\n• Write and debug code\n• Explain complex concepts\n• Help with math and science\n• Have conversations\n\nThink of me as a knowledgeable friend who's always ready to help!";
        }

        // Check for name questions
        if (stripos($message, 'your name') !== false) {
            return "My name is Shark AI! \n\nI'm a King of the water  designed to help you with questions, coding, writing, and much more. How can I assist you today?";
        }

        // Try DuckDuckGo as a last resort
        $ddgAnswer = $this->getInstantAnswer($message);
        if ($ddgAnswer) {
            return $ddgAnswer;
        }

        // Try Wikipedia
        $wikiAnswer = $this->getWikipediaAnswer($message);
        if ($wikiAnswer) {
            return $wikiAnswer;
        }

        // Generate a helpful response based on the question type
        if (preg_match('/^(what is|what are|what\'s)/i', $message)) {
            $topic = preg_replace('/^(what is|what are|what\'s)\s+/i', '', $message);
            return "I'd be happy to help explain what **" . ucfirst($topic) . "** is!\n\nUnfortunately, I'm having trouble connecting to my knowledge base right now.\n\nTry:\n• Asking me again in a moment\n• Phrasing your question differently\n• Checking back once Ollama finishes downloading\n\nWould you like me to try answering in a different way?";
        }

        if (preg_match('/^(how do|how can|how to)/i', $message)) {
            return "That's a great question! \n\nI'm having some trouble connecting to my knowledge base at the moment, but I'd love to help you with that.\n\nCould you try asking again in a few moments? Or perhaps rephrase your question?\n\nIn the meantime, I can try to help if you tell me more about what you're trying to accomplish.";
        }

        if (preg_match('/^(why is|why does|why did)/i', $message)) {
            return "That's an interesting question about why something is the way it is! \n\nI'm having some trouble connecting to my knowledge base right now, but I'd love to help you with that.\n\nCould you try again in a moment? Or perhaps rephrase the question?";
        }

        // Default engaging response
        return "That's an interesting question! I'm here to help.\n\n**Here's what I can help you with:**\n• Answering questions on any topic\n• Writing and explaining code\n• Solving problems\n• Having conversations\n\n**Try:**\n• Asking me your question again\n• Rephrasing your question\n\nI'm ready to help - just ask me anything!";
    }

    /**
     * Check if a question is asking for definition/explanation
     */
    public function isDefinitionQuestion(string $message): bool
    {
        $definitionPatterns = [
            '/^what is (a |an |the )?/i',
            '/^what are /i',
            '/^what does /i',
            '/^define /i',
            '/^explain /i',
            '/^how does /i',
            '/^why is /i',
            '/^who is /i',
            '/^who was /i',
            '/^when did /i',
            '/^where is /i',
        ];

        foreach ($definitionPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get streaming response from OpenRouter API
     * Returns chunks of the AI response as they're generated
     */
    public function getStreamingResponse(string $message, array $conversationHistory = [], ?string $model = null, ?callable $onChunk = null)
    {
        $model = $model ?? config('services.ai.default_model', self::DEFAULT_MODEL);
        $apiKey = env('OPENROUTER_API_KEY');

        if (empty($apiKey)) {
            \Log::warning('OpenRouter API key is empty for streaming');
            yield $this->getResponse($message, $conversationHistory, $model);
            return;
        }

        try {
            // Map model aliases to OpenRouter model IDs
            $modelMap = [
                'gpt-4o' => 'openai/gpt-4o',
                'gpt-4-turbo' => 'openai/gpt-4-turbo',
                'gpt-4' => 'openai/gpt-4',
                'gpt-3.5-turbo' => 'openai/gpt-3.5-turbo',
                'claude-3-opus' => 'anthropic/claude-3-opus',
                'claude-3-sonnet' => 'anthropic/claude-3-sonnet',
                'llama-3.1-70b' => 'meta-llama/llama-3.1-70b-instruct',
                'mistral-7b' => 'mistralai/mistral-7b-instruct',
            ];

            $routerModel = $modelMap[$model] ?? $model ?? 'mistralai/mistral-7b-instruct';

            // Build conversation context
            $messages = [];

            // Add system message
            $messages[] = [
                'role' => 'system',
                'content' => 'You are Shark AI, a helpful, friendly, and knowledgeable AI assistant. Always provide accurate, factual, and correct answers to any questions. Be concise but thorough. Provide accurate and helpful responses.'
            ];

            // Add conversation history
            foreach ($conversationHistory as $msg) {
                $messages[] = [
                    'role' => $msg['role'] ?? 'user',
                    'content' => $msg['content'] ?? ''
                ];
            }

            // Add current message
            $messages[] = [
                'role' => 'user',
                'content' => $message
            ];

            // Use curl for streaming support
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => 'https://openrouter.ai/api/v1/chat/completions',
                CURLOPT_RETURNTRANSFER => false,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $apiKey,
                    'Content-Type: application/json',
                    'HTTP-Referer: https://sharkgpt.com',
                    'X-Title: Shark AI'
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode([
                    'model' => $routerModel,
                    'messages' => $messages,
                    'max_tokens' => 4096,
                    'temperature' => 0.3,
                    'stream' => true
                ]),
                CURLOPT_TIMEOUT => 60,
                CURLOPT_WRITEFUNCTION => function ($curl, $data) use ($onChunk) {
                    // Parse server-sent events
                    $lines = explode("\n", $data);

                    foreach ($lines as $line) {
                        if (strpos($line, 'data: ') === 0) {
                            $json = substr($line, 6);
                            if ($json === '[DONE]') {
                                break;
                            }

                            $chunk = json_decode($json, true);
                            if (isset($chunk['choices'][0]['delta']['content'])) {
                                $content = $chunk['choices'][0]['delta']['content'];
                                if ($onChunk) {
                                    call_user_func($onChunk, $content);
                                }
                                yield $content;
                            }
                        }
                    }

                    return strlen($data);
                }
            ]);

            curl_exec($ch);
        } catch (\Exception $e) {
            \Log::error('OpenRouter streaming error: ' . $e->getMessage());
            yield $this->getResponse($message, $conversationHistory, $model);
        }
    }
}
