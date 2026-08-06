<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Project;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChatCreateComponent extends Component
{
    use WithFileUploads;

    protected $rules = [
        'initialMessage' => 'required|string|max:4000',
    ];

    public $initialMessage = '';
    public $projectId = null;
    public $projects = [];
    public $isGroupChat = false;
    public $isLoading = false;
    public $messages = [];
    public $chat = null;
    public $selectedModel = 'gpt-4-turbo';

    public function mount()
    {
        $this->projects = Auth::user()->projects()->pluck('title', 'id')->toArray();
        $this->messages = [];
    }

    public function setSelectedModel($model)
    {
        $this->selectedModel = $model;
    }

    public function setPrompt($prompt)
    {
        $this->initialMessage = $prompt;
    }

    protected function buildConversationHistory(): array
    {
        $history = array_map(function ($message) {
            return [
                'role' => $message['role'] ?? 'user',
                'content' => $message['content'] ?? '',
            ];
        }, $this->messages);

        if (!empty($history) && end($history)['role'] === 'user') {
            array_pop($history);
        }

        return $history;
    }

    protected function getLastUserMessage(): string
    {
        for ($i = count($this->messages) - 1; $i >= 0; $i--) {
            if (($this->messages[$i]['role'] ?? '') === 'user') {
                return $this->messages[$i]['content'] ?? '';
            }
        }

        return $this->initialMessage;
    }

    public function createChat()
    {
        $this->validate();

        $user = Auth::user();

        // Generate a title from the first few words of the message
        $title = Str::limit($this->initialMessage, 50, '...');

        $chat = $user->chats()->create([
            'title' => $title,
            'uuid' => Str::uuid(),
            'session_id' => Str::random(16),
            'project_id' => $this->projectId,
        ]);

        $this->chat = $chat;

        // Create the initial user message
        $userMessage = Message::create([
            'chat_id' => $chat->id,
            'role' => 'user',
            'content' => $this->initialMessage,
        ]);

        // Add user message to messages array
        $this->messages[] = $userMessage->toArray();

        // Reset input text and redirect to the session URL
        $this->initialMessage = '';

        return redirect()->route('chats.session.show', $chat->session_id);
    }

    public function generateAIResponse()
    {
        $this->isLoading = true;

        try {
            $aiService = new AIService();
            $conversationHistory = $this->buildConversationHistory();
            $response = $aiService->getResponse($this->getLastUserMessage(), $conversationHistory, $this->selectedModel);

            \Log::info('AI Response received', ['response_length' => strlen($response), 'response_preview' => substr($response, 0, 100)]);

            // Create AI response message
            $aiMessage = Message::create([
                'chat_id' => $this->chat->id,
                'content' => $response,
                'role' => 'assistant',
            ]);

            // Add AI message to messages array
            $this->messages[] = $aiMessage->toArray();
        } catch (\Exception $e) {
            // Create error message
            $errorMessage = Message::create([
                'chat_id' => $this->chat->id,
                'content' => 'Sorry, I encountered an error while generating a response. Please try again.',
                'role' => 'assistant',
            ]);

            $this->messages[] = $errorMessage->toArray();
            \Log::error('AI Response Error: ' . $e->getMessage());
        }

        $this->isLoading = false;
    }

    public function sendMessage()
    {
        $this->validate(['initialMessage' => 'required|string|max:4000']);

        $this->isLoading = true;

        // Create user message
        $userMessage = Message::create([
            'chat_id' => $this->chat->id,
            'content' => $this->initialMessage,
            'role' => 'user',
        ]);

        $this->messages[] = $userMessage->toArray();
        $this->initialMessage = '';

        // Generate AI response
        $this->generateAIResponse();

        $this->isLoading = false;
    }

    public function handleFileUpload($file)
    {
        try {
            $path = $file->store('chat-uploads', 'public');
            $fileName = $file->getClientOriginalName();

            // Add file reference to message
            $this->initialMessage .= "\n[File uploaded: {$fileName}]";
        } catch (\Exception $e) {
            \Log::error('File Upload Error: ' . $e->getMessage());
            $this->addError('initialMessage', 'Failed to upload file.');
        }
    }

    public function audioRecorded($audio)
    {
        try {
            // Handle audio recording if needed
            // This can be extended to transcribe audio to text using a service
            $this->initialMessage .= "\n[Audio message recorded]";
        } catch (\Exception $e) {
            \Log::error('Audio Recording Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return View::make('livewire.chats.create')
            ->layout('components.layouts.app');
    }
}
