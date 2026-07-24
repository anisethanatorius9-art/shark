<div class="flex h-screen bg-white dark:bg-zinc-900 text-gray-900 dark:text-white">
    <div class="flex-1 flex flex-col">
        <!-- Messages Area - Flex 1 to fill space -->
        <div class="flex-1 overflow-y-auto flex flex-col">
            <div class="flex-1 flex items-center justify-center">
                <div class="max-w-4xl mx-auto px-6 py-6 w-full">
                    @if(empty($this->messages))
                    <div class="flex flex-col items-center justify-center">
                        <h2 class="text-4xl font-bold mb-3">How can I help you today?</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">

                        </p>
                    </div>
                    @else
                    <div class="space-y-4">
                        @if(is_array($this->messages) && count($this->messages) > 0)
                        <?php foreach ($this->messages as $message): ?>
                            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xl px-5 py-3 rounded-3xl text-[15px] leading-7 whitespace-pre-wrap {{ $message['role'] === 'user' ? 'bg-blue-600 text-white rounded-3xl shadow-md' : 'bg-gray-100 dark:bg-zinc-800 text-gray-900 dark:text-white' }}">
                                    {{ $message['content'] }}
                                </div>
                            </div>
                        <?php endforeach; ?>
                        @endif

                        @if($this->isLoading)
                        <div class="flex justify-start">
                            <div class="flex items-center gap-2 px-5 py-3 rounded-3xl bg-gray-100 dark:bg-zinc-800">
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Input Area - No Border -->
        <div class="px-6 py-6 bg-white dark:bg-zinc-900">
            <div class="max-w-4xl mx-auto">
                @if(!$this->chat)
                <form wire:submit.prevent="createChat" class="space-y-3">
                    <div class="relative flex items-center gap-3 bg-gray-100 dark:bg-zinc-800 rounded-full px-4 py-3 border border-gray-200 dark:border-zinc-700 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition">
                        <!-- File Upload Input -->
                        <input type="file" id="fileUpload" class="hidden" wire:change="handleFileUpload">

                        <!-- Plus Button - File Upload -->
                        <button type="button" onclick="document.getElementById('fileUpload').click()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>

                        <!-- Textarea -->
                        <textarea
                            wire:model.live="initialMessage"
                            placeholder="Ask anything"
                            class="flex-1 bg-transparent text-gray-900 dark:text-white text-base placeholder-gray-400 dark:placeholder-gray-500 border-0 focus:outline-none resize-none"
                            rows="1"
                            style="min-height: 24px; max-height: 120px;"></textarea>

                        <!-- Microphone Button - Record Audio -->
                        <button
                            type="button"
                            @click="toggleRecording"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                            x-data="{ recording: false }"
                            @click="recording = !recording">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" x-show="!recording">
                                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" />
                                <path d="M19 10v2a7 7 0 01-14 0v-2m0 0a1 1 0 011-1h12a1 1 0 011 1v2a9 9 0 01-18 0v-2a1 1 0 011-1z" />
                            </svg>
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24" x-show="recording">
                                <circle cx="12" cy="12" r="8" />
                            </svg>
                        </button>

                        <!-- Voice/Send Button -->
                        <button
                            type="submit"
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-black dark:bg-white flex items-center justify-center hover:opacity-80 transition"
                            wire:loading.disabled>
                            <svg class="w-4 h-4 text-white dark:text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('initialMessage')
                    <flux:error>{{ $message }}</flux:error>
                    @enderror

                    @if(count($this->projects) > 0)
                    <div class="flex items-center gap-3 flex-wrap mt-3">
                        <flux:label>Project (optional)</flux:label>
                        <flux:select
                            wire:model="projectId"
                            class="flex-1 min-w-max">
                            <option value="">No project</option>
                            <?php foreach ($this->projects as $id => $title): ?>
                                <option value="{{ $id }}">{{ $title }}</option>
                            <?php endforeach; ?>
                        </flux:select>
                    </div>
                    @endif
                </form>
                @else
                <form wire:submit.prevent="sendMessage" class="space-y-3">
                    <div class="relative flex items-center gap-3 bg-gray-100 dark:bg-zinc-800 rounded-full px-4 py-3 border border-gray-200 dark:border-zinc-700 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition">
                        <!-- File Upload Input -->
                        <input type="file" id="fileUpload" class="hidden" wire:change="handleFileUpload">

                        <!-- Plus Button - File Upload -->
                        <button type="button" onclick="document.getElementById('fileUpload').click()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>

                        <!-- Textarea -->
                        <textarea
                            wire:model.live="initialMessage"
                            wire:keydown.enter.prevent="sendMessage"
                            placeholder="Continue the conversation..."
                            class="flex-1 bg-transparent text-gray-900 dark:text-white text-base placeholder-gray-400 dark:placeholder-gray-500 border-0 focus:outline-none resize-none"
                            rows="1"
                            style="min-height: 24px; max-height: 120px;"></textarea>

                        <!-- Microphone Button - Record Audio -->
                        <button
                            type="button"
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition"
                            x-data="{ recording: false }"
                            @click="recording = !recording">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" x-show="!recording">
                                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" />
                                <path d="M19 10v2a7 7 0 01-14 0v-2m0 0a1 1 0 011-1h12a1 1 0 011 1v2a9 9 0 01-18 0v-2a1 1 0 011-1z" />
                            </svg>
                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24" x-show="recording">
                                <circle cx="12" cy="12" r="8" />
                            </svg>
                        </button>

                        <!-- Voice/Send Button -->
                        <button
                            type="submit"
                            class="flex-shrink-0 w-8 h-8 rounded-full bg-black dark:bg-white flex items-center justify-center hover:opacity-80 transition"
                            wire:loading.disabled>
                            <svg class="w-4 h-4 text-white dark:text-black" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('initialMessage')
                    <flux:error>{{ $message }}</flux:error>
                    @enderror
                </form>
                @endif
                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                    AI can make mistakes. Please verify important information.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        let mediaRecorder;
        let audioChunks = [];

        window.toggleRecording = async function() {
            if (!mediaRecorder) {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, {
                        type: 'audio/mp3'
                    });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    // Emit to Livewire or handle as needed
                    Livewire.dispatch('audioRecorded', {
                        audio: audioUrl
                    });
                };

                mediaRecorder.start();
            } else if (mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                mediaRecorder = null;
            }
        };

        // Load selected model from localStorage
        const selectedModel = localStorage.getItem('selected_model') || 'gpt-4-turbo';
        Livewire.find('@this.id').set('selectedModel', selectedModel);
    });
</script>
