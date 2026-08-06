<div class="flex min-h-screen bg-slate-50 dark:bg-zinc-950 text-slate-900 dark:text-white">
    <div class="flex-1 flex flex-col">
        <div class="flex-1 overflow-hidden">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="rounded-[2rem] border border-slate-200/80 bg-white/90 shadow-2xl shadow-slate-200/20 backdrop-blur-xl dark:border-zinc-800/80 dark:bg-zinc-900/90">
                    <div class="grid gap-8 lg:grid-cols-[1.3fr,_0.7fr]">
                        <div class="p-8 lg:p-10">
                            @if(empty($messages))
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-blue-600 dark:text-blue-400">New Chat</p>
                                        <h1 class="mt-3 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white">Start a new AI chat</h1>
                                        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-400">Type your question below. Try asking: "Does it provide answers or is it unsure?"</p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 dark:border-zinc-700 dark:bg-zinc-950 dark:text-slate-300">
                                        AI response style
                                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Click a suggestion or type your question.</p>
                                        <div class="mt-5 grid gap-3">
                                            <button type="button" wire:click="setPrompt('Does it provide answers?')" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-medium text-slate-900 hover:border-slate-300 hover:bg-slate-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:hover:border-zinc-600 dark:hover:bg-zinc-800">Does it provide answers?</button>
                                            <button type="button" wire:click="setPrompt('Does this chat help answer my questions?')" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-medium text-slate-900 hover:border-slate-300 hover:bg-slate-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:hover:border-zinc-600 dark:hover:bg-zinc-800">Does this chat help answer my questions?</button>
                                        </div>
                                    </div>

                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 dark:border-zinc-800 dark:bg-zinc-950">
                                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Try this</h2>
                                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">These questions help check whether the system returns sensible answers.</p>
                                        <div class="mt-5 space-y-3">
                                            <button type="button" wire:click="setPrompt('Does it give clear answers or just repeat?')" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-medium text-slate-900 hover:border-slate-300 hover:bg-slate-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:hover:border-zinc-600 dark:hover:bg-zinc-800">Does it give clear answers or just repeat?</button>
                                            <button type="button" wire:click="setPrompt('Help me understand how this can be used for everyday questions.')" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-medium text-slate-900 hover:border-slate-300 hover:bg-slate-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:hover:border-zinc-600 dark:hover:bg-zinc-800">Help me understand how this can be used for everyday questions.</button>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(!empty($messages))
                                @if($chat && $chat->session_id)
                                <div class="mb-4 rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-500 dark:border-zinc-700 dark:bg-zinc-900 dark:text-slate-400">
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">System / Session ID:</span>
                                    {{ $chat->session_id }}
                                </div>
                                @endif
                                <div class="space-y-4 mt-4">
                                    @foreach($messages as $message)
                                    <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-2xl rounded-3xl px-5 py-4 leading-7 whitespace-pre-wrap {{ $message['role'] === 'user' ? 'bg-blue-600 text-white shadow-2xl' : 'bg-slate-100 text-slate-900 dark:bg-zinc-800 dark:text-white' }}">
                                            {{ $message['content'] }}
                                        </div>
                                    </div>
                                    @endforeach

                                    @if($isLoading)
                                    <div class="flex justify-start">
                                        <div class="flex items-center gap-2 rounded-3xl bg-slate-100 px-4 py-3 dark:bg-zinc-800">
                                            <span class="h-2 w-2 animate-bounce rounded-full bg-slate-500 dark:bg-slate-400"></span>
                                            <span class="h-2 w-2 animate-bounce rounded-full bg-slate-500 dark:bg-slate-400 delay-150"></span>
                                            <span class="h-2 w-2 animate-bounce rounded-full bg-slate-500 dark:bg-slate-400 delay-300"></span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(empty($messages))
                        <div class="border-l border-slate-200/90 bg-slate-50 p-8 dark:border-zinc-800/90 dark:bg-zinc-950 lg:p-10">
                            <div class="space-y-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">You can ask</h2>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Here are some ideas to get started.</p>
                                </div>
                                <div class="grid gap-3">
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Example question</p>
                                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">Does this Ai provide accurate answers?</p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                                        <p class="text-sm text-slate-600 dark:text-slate-400">Tip</p>
                                        <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">Use clear, specific questions to get better answers.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-200/80 dark:border-zinc-800/80 bg-slate-50 dark:bg-zinc-900">
                        <div class="px-6 py-6">
                            <div class="max-w-4xl mx-auto">
                                @if(!$chat)
                                <form wire:submit.prevent="createChat" class="space-y-3">
                                    <div class="relative flex items-center gap-3 rounded-full border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
                                        <input type="file" id="fileUpload" class="hidden" wire:change="handleFileUpload">
                                        <button type="button" onclick="document.getElementById('fileUpload').click()" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-300 dark:hover:text-white transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                        <textarea
                                            wire:model.live="initialMessage"
                                            wire:keydown.enter.prevent="createChat"
                                            placeholder="Ask anything..."
                                            class="flex-1 bg-transparent text-slate-900 dark:text-white text-base placeholder-slate-400 dark:placeholder-slate-500 border-0 focus:outline-none resize-none"
                                            rows="1"
                                            style="min-height: 24px; max-height: 140px;"></textarea>
                                        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-300 dark:hover:text-white transition" x-data="{ recording: false }" @click="recording = !recording">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" x-show="!recording">
                                                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" />
                                                <path d="M19 10v2a7 7 0 01-14 0v-2m0 0a1 1 0 011-1h12a1 1 0 011 1v2a9 9 0 01-18 0v-2a1 1 0 011-1z" />
                                            </svg>
                                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24" x-show="recording">
                                                <circle cx="12" cy="12" r="8" />
                                            </svg>
                                        </button>
                                        <button type="submit" class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 flex items-center justify-center hover:opacity-90 transition" wire:loading.disabled>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('initialMessage')
                                    <flux:error>{{ $message }}</flux:error>
                                    @enderror
                                    @if(count($projects) > 0)
                                    <div class="flex items-center gap-3 flex-wrap mt-3">
                                        <flux:label>Project (optional)</flux:label>
                                        <flux:select wire:model="projectId" class="flex-1 min-w-max">
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
                                    <div class="relative flex items-center gap-3 rounded-full border border-slate-200 bg-white px-4 py-3 shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
                                        <input type="file" id="fileUpload" class="hidden" wire:change="handleFileUpload">
                                        <button type="button" onclick="document.getElementById('fileUpload').click()" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-300 dark:hover:text-white transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                        <textarea
                                            wire:model.live="initialMessage"
                                            wire:keydown.enter.prevent="sendMessage"
                                            placeholder="Continue the conversation..."
                                            class="flex-1 bg-transparent text-slate-900 dark:text-white text-base placeholder-slate-400 dark:placeholder-slate-500 border-0 focus:outline-none resize-none"
                                            rows="1"
                                            style="min-height: 24px; max-height: 140px;"></textarea>
                                        <button type="button" class="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-300 dark:hover:text-white transition" x-data="{ recording: false }" @click="recording = !recording">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" x-show="!recording">
                                                <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z" />
                                                <path d="M19 10v2a7 7 0 01-14 0v-2m0 0a1 1 0 011-1h12a1 1 0 011 1v2a9 9 0 01-18 0v-2a1 1 0 011-1z" />
                                            </svg>
                                            <svg class="w-5 h-5 text-red-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24" x-show="recording">
                                                <circle cx="12" cy="12" r="8" />
                                            </svg>
                                        </button>
                                        <button type="submit" class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-900 text-white dark:bg-white dark:text-slate-900 flex items-center justify-center hover:opacity-90 transition" wire:loading.disabled>
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('initialMessage')
                                    <flux:error>{{ $message }}</flux:error>
                                    @enderror
                                </form>
                                @endif
                                <p class="text-center text-xs text-slate-500 dark:text-slate-400 mt-2">AI can make mistakes. Please verify important information before using it.</p>
                            </div>
                        </div>
                    </div>
                </div>
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

        const selectedModel = localStorage.getItem('selected_model') || 'gpt-4-turbo';
        document.addEventListener('livewire:load', () => {
            const component = window.Livewire && window.Livewire.find('{{ $_instance->getId() }}');
            if (component && typeof component.set === 'function') {
                component.set('selectedModel', selectedModel);
            }
        });
    });
</script>