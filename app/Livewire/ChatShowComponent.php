<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatShowComponent extends Component
{
    use WithFileUploads;

    public Chat $chat;
    public $messages = [];
    public $messageText = '';
    public $uploadedFile = null;
    public $selectedModel = 'gpt-4o';
    public $isLoading = false;
    public $scrollToBottom = true;
    public $recentChats = [];
    public $isTyping = false;
    public $typingUsers = [];
    public $showTypingIndicator = false;
    public $streamingEnabled = false;
    public $chunkDelay = 100;

    #[\Livewire\Attributes\On('refresh-messages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->markMessagesAsDelivered();
    }

    #[\Livewire\Attributes\On('user-typing')]
    public function handleUserTyping($userId)
    {
        if ($userId !== Auth::id()) {
            $this->showTypingIndicator = true;
        }
    }

    #[\Livewire\Attributes\On('user-stopped-typing')]
    public function handleUserStoppedTyping($userId)
    {
        if ($userId !== Auth::id()) {
            $this->showTypingIndicator = false;
        }
    }

    public function setSelectedModel($model)
    {
        $this->selectedModel = $model;
    }

    public function mount($uuid)
    {
        $chat = Auth::user()->chats()->where('uuid', $uuid)->firstOrFail();

        $this->chat = $chat;
        $this->loadMessages();
        $this->loadRecentChats();
        $this->markMessagesAsDelivered();

        // If this is a new chat with only one user message, generate AI response
        $messageCount = $this->chat->messages()->count();
        if ($messageCount === 1) {
            $firstMessage = $this->chat->messages()->first();
            if ($firstMessage && $firstMessage->role === 'user') {
                $this->generateAIResponse();
            }
        }
    }

    public function loadMessages()
    {
        $this->messages = $this->chat->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'role' => $message->role,
                    'status' => $message->status,
                    'created_at' => $message->created_at->format('H:i'),
                    'read_at' => $message->read_at,
                    'delivered_at' => $message->delivered_at,
                    'isOwn' => $message->role === 'user' && $message->chat->user_id === Auth::id(),
                ];
            })
            ->toArray();
    }

    public function loadRecentChats()
    {
        $this->recentChats = Auth::user()->chats()
            ->with('messages')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($chat) {
                $lastMessage = $chat->messages()->latest()->first();
                return [
                    'id' => $chat->id,
                    'uuid' => $chat->uuid,
                    'title' => $chat->title,
                    'lastMessage' => $lastMessage?->content ? (strlen($lastMessage->content) > 50 ? substr($lastMessage->content, 0, 50) . '...' : $lastMessage->content) : 'No messages',
                    'unreadCount' => $chat->messages()->where('status', '!=', 'read')->count(),
                ];
            });
    }

    /**
     * Mark all messages in chat as delivered to current user
     */
    public function markMessagesAsDelivered()
    {
        $undeliveredMessages = $this->chat->messages()
            ->where('status', 'sent')
            ->get();

        foreach ($undeliveredMessages as $message) {
            $message->markAsDelivered();
        }
    }

    /**
     * Mark all messages as read by current user
     */
    public function markMessagesAsRead()
    {
        $unreadMessages = $this->chat->messages()
            ->where('status', '!=', 'read')
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsRead();
        }

        $this->loadMessages();
    }

    public function sendMessage()
    {
        $this->validate(['messageText' => 'required|string|max:4000']);

        $this->isLoading = true;
        $this->isTyping = true;

        // Broadcast typing indicator
        $this->dispatch('user-typing', Auth::id())->toOthers();

        try {
            // Create user message with 'sent' status
            $userMessage = Message::create([
                'chat_id' => $this->chat->id,
                'content' => $this->messageText,
                'role' => 'user',
                'status' => 'sent',
            ]);

            $this->loadMessages();
            $this->messageText = '';
            $this->uploadedFile = null;

            // Broadcast message sent
            $this->dispatch('message-sent', $userMessage->id)->toOthers();

            // Generate AI response
            $this->generateAIResponse();
        } catch (\Exception $e) {
            \Log::error('Error sending message: ' . $e->getMessage());
        } finally {
            $this->isTyping = false;
            $this->dispatch('user-stopped-typing', Auth::id())->toOthers();
            $this->isLoading = false;
        }
    }

    public function generateAIResponse()
    {
        $this->isLoading = true;

        try {
            // Check if streaming is enabled for this user
            $user = Auth::user();
            $settings = $user->settings ?? [];
            $streamingEnabled = $settings['streaming_enabled'] ?? false;

            // Get conversation history
            $messages = $this->chat->messages()
                ->orderBy('created_at', 'asc')
                ->get();

            // Get the last user message
            $lastUserMessage = $messages->where('role', 'user')->last();
            if (!$lastUserMessage) {
                $this->isLoading = false;
                return;
            }

            // Build conversation history
            $conversationHistory = $messages->filter(function ($message) use ($lastUserMessage) {
                return $message->id !== $lastUserMessage->id;
            })->map(function ($message) {
                return [
                    'role' => $message->role,
                    'content' => $message->content,
                ];
            })->toArray();

            $aiService = new AIService();

            if ($streamingEnabled) {
                // Use streaming response
                $response = '';
                $chunkDelay = $settings['chunk_delay'] ?? 100;

                // Create message placeholder
                $aiMessage = Message::create([
                    'chat_id' => $this->chat->id,
                    'content' => '',
                    'role' => 'assistant',
                    'status' => 'delivered',
                    'delivered_at' => now(),
                ]);

                // Stream response chunks
                foreach ($aiService->getStreamingResponse($lastUserMessage->content, $conversationHistory, $this->selectedModel) as $chunk) {
                    $response .= $chunk;
                    $aiMessage->update(['content' => $response]);

                    // Dispatch partial response
                    $this->dispatch(
                        'streaming-chunk',
                        messageId: $aiMessage->id,
                        content: $response,
                        delay: $chunkDelay
                    )->toOthers();

                    // Small delay between chunks for better UX
                    usleep($chunkDelay * 1000);
                }

                $aiMessage->markAsRead();
            } else {
                // Use regular response
                $response = $aiService->getResponse($lastUserMessage->content, $conversationHistory, $this->selectedModel);

                \Log::info('AI Response received', ['response_length' => strlen($response)]);

                // Create AI response message with 'delivered' status
                $aiMessage = Message::create([
                    'chat_id' => $this->chat->id,
                    'content' => $response,
                    'role' => 'assistant',
                    'status' => 'delivered',
                    'delivered_at' => now(),
                ]);

                // Mark as read immediately
                $aiMessage->markAsRead();
            }

            $this->loadMessages();
            $this->dispatch('ai-response-generated');
            $this->dispatch('message-added', $aiMessage->id)->toOthers();
        } catch (\Exception $e) {
            \Log::error('AI Response Error: ' . $e->getMessage());

            // Create error message
            Message::create([
                'chat_id' => $this->chat->id,
                'content' => '❌ Sorry, I encountered an error while generating a response. Please try again.',
                'role' => 'assistant',
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);

            $this->loadMessages();
        }

        $this->isLoading = false;
    }

    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);

        // Only allow deleting own user messages
        if ($message->chat_id !== $this->chat->id || ($message->role === 'user' && $message->chat->user_id !== Auth::id())) {
            abort(403);
        }

        $message->delete();
        $this->loadMessages();
        $this->dispatch('message-deleted', $messageId)->toOthers();
    }

    public function renameChat($newName = null)
    {
        if (!$newName || strlen(trim($newName)) === 0) {
            $newName = 'Untitled Chat';
        }

        $this->chat->update(['title' => trim($newName)]);
        $this->loadRecentChats();
        $this->dispatch('chat-renamed', $newName);
    }

    public function archiveChat()
    {
        $this->chat->update(['archived_at' => now()]);
        return redirect()->route('chats.create')->with('success', 'Chat archived!');
    }

    public function deleteChat()
    {
        $chat = $this->chat;
        if ($chat->user_id !== Auth::id()) {
            abort(403);
        }

        $chat->delete();
        return redirect()->route('chats.create')->with('success', 'Chat deleted!');
    }

    public function copyMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        if ($message->chat_id !== $this->chat->id) {
            abort(403);
        }

        // Note: Actual copying happens on frontend with JS
        $this->dispatch('message-copied', $messageId);
    }

    public function getAvailableModels()
    {
        return array_keys(AIService::MODELS);
    }

    public function render()
    {
        return view('livewire.chats.show')
            ->layout('components.layouts.app');
    }
}
