<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Services\AIService;
use Livewire\Attributes\Validate;

class StreamingSettingsComponent extends Component
{
    /**
     * Enable/disable streaming
     * @var bool
     */
    #[Validate('boolean')]
    public bool $streamingEnabled = false;

    /**
     * Selected AI model for streaming
     * @var string
     */
    #[Validate('string')]
    public string $selectedModel = 'gpt-4o';

    /**
     * Streaming chunk delay (in milliseconds)
     * @var int
     */
    #[Validate('integer|min:10|max:1000')]
    public int $chunkDelay = 100;

    /**
     * Loading state for feedback
     * @var bool
     */
    public bool $isSaving = false;

    /**
     * Streaming test output
     * @var string
     */
    public string $testOutput = '';

    /**
     * Whether a streaming test is currently running
     * @var bool
     */
    public bool $isTesting = false;

    /**
     * Notification message for the page
     * @var string
     */
    public string $notificationMessage = '';

    /**
     * Notification type for the page
     * @var string
     */
    public string $notificationType = 'success';

    /**
     * Available models
     * @var array<string, string>
     */
    public array $availableModels = [
        'gpt-4o' => 'GPT-4o (Fast & Accurate)',
        'gpt-4-turbo' => 'GPT-4 Turbo (Powerful)',
        'gpt-4' => 'GPT-4 (Balanced)',
        'gpt-3.5-turbo' => 'GPT-3.5 Turbo (Fast)',
        'claude-3-opus' => 'Claude 3 Opus (Advanced)',
        'claude-3-sonnet' => 'Claude 3 Sonnet (Balanced)',
        'llama-3.1-70b' => 'Llama 3.1 70B (Open Source)',
        'mistral-7b' => 'Mistral 7B (Efficient)',
    ];

    /**
     * Mount component
     */
    public function mount()
    {
        $user = auth()->user();

        // Load user preferences from settings
        $settings = $user->settings ?? [];
        $this->streamingEnabled = $settings['streaming_enabled'] ?? false;
        $this->selectedModel = $settings['streaming_model'] ?? 'gpt-4o';
        $this->chunkDelay = $settings['chunk_delay'] ?? 100;
    }

    /**
     * Toggle streaming with immediate feedback
     */
    public function toggleStreaming()
    {
        $this->streamingEnabled = !$this->streamingEnabled;
        $this->saveSettings();
    }

    /**
     * Update streaming model with immediate visual feedback
     */
    public function updateModel(string $model): void
    {
        $this->selectedModel = $model;
        $this->saveSettings();
    }

    /**
     * Update chunk delay with validation
     */
    public function updateChunkDelay(int|string $delay): void
    {
        $delay = (int)$delay;
        $this->chunkDelay = max(10, min(1000, $delay));
        $this->saveSettings();
    }

    /**
     * Save settings to database with optimized performance
     */
    private function saveSettings()
    {
        try {
            $user = auth()->user();
            $settings = $user->settings ?? [];

            $settings['streaming_enabled'] = $this->streamingEnabled;
            $settings['streaming_model'] = $this->selectedModel;
            $settings['chunk_delay'] = $this->chunkDelay;

            $user->update(['settings' => $settings]);

            // Dispatch events for other components to listen to
            $this->dispatch('streaming-toggled', enabled: $this->streamingEnabled);
            $this->dispatch('streaming-model-changed', model: $this->selectedModel);
            $this->dispatch('chunk-delay-changed', delay: $this->chunkDelay);
        } catch (\Exception $e) {
            // Log error but don't throw to user
            \Log::error('Streaming settings save error: ' . $e->getMessage());
        }
    }

    /**
     * Test streaming with proper error handling
     */
    public function testStreaming()
    {
        if (!$this->streamingEnabled) {
            $this->notificationType = 'warning';
            $this->notificationMessage = 'Please enable streaming first!';
            return;
        }

        $this->isTesting = true;
        $this->testOutput = '';
        $this->notificationType = 'info';
        $this->notificationMessage = 'Running streaming test...';

        try {
            $aiService = new AIService();
            $prompt = 'Generate a short example of a streaming response from Shark AI in a friendly tone.';

            $response = '';
            foreach ($aiService->getStreamingResponse($prompt, [], $this->selectedModel) as $chunk) {
                $response .= $chunk;
            }

            if (empty(trim($response))) {
                $response = 'Streaming test completed, but no response was returned. Please check your API settings.';
            }

            $this->testOutput = trim($response);
            $this->notificationType = 'success';
            $this->notificationMessage = 'Streaming test completed successfully.';
        } catch (\Throwable $e) {
            \Log::error('Streaming test error: ' . $e->getMessage());
            $this->notificationType = 'error';
            $this->notificationMessage = 'Streaming test failed. Please check your network and API credentials.';
            $this->testOutput = '';
        } finally {
            $this->isTesting = false;
            $this->dispatch('test-streaming', model: $this->selectedModel);
        }
    }

    /**
     * Reset to default settings
     */
    public function resetToDefaults()
    {
        $this->streamingEnabled = false;
        $this->selectedModel = 'gpt-4o';
        $this->chunkDelay = 100;
        $this->saveSettings();
        $this->dispatch('notify', message: 'Settings reset to defaults', type: 'success');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.streaming-settings', [
            'availableModels' => $this->availableModels,
        ]);
    }
}
