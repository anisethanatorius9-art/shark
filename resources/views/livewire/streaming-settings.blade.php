<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 py-12 px-4" x-data="{}">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Message Streaming Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Configure how AI responses are displayed in real-time</p>
        </div>

        <!-- Main Settings Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-lg p-8">
            <!-- Streaming Toggle -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Enable Message Streaming</h2>
                        <p class="text-gray-600 dark:text-gray-400">Display AI responses as they're generated, character by character</p>
                    </div>
                    <button
                        wire:click="toggleStreaming"
                        type="button"
                        @class([ 'relative inline-flex h-8 w-16 items-center rounded-full transition-all duration-200' , 'bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700'=> $streamingEnabled,
                        'bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-700' => !$streamingEnabled,
                        ])>
                        <span @class([ 'inline-flex h-7 w-7 transform rounded-full bg-white transition-transform duration-200 shadow-lg' , 'translate-x-9'=> $streamingEnabled,
                            'translate-x-1' => !$streamingEnabled,
                            ])></span>
                    </button>
                </div>
            </div>

            <!-- Status Indicator -->
            <div @class([ 'mb-8 p-4 rounded-lg border' , 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-700/50'=> $streamingEnabled,
                'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600' => !$streamingEnabled,
                ])>
                <div class="flex items-center gap-3">
                    <div @class([ 'w-3 h-3 rounded-full' , 'bg-green-500 animate-pulse'=> $streamingEnabled,
                        'bg-gray-400' => !$streamingEnabled,
                        ])></div>
                    <p @class([ 'text-sm font-medium' , 'text-green-700 dark:text-green-400'=> $streamingEnabled,
                        'text-gray-600 dark:text-gray-400' => !$streamingEnabled,
                        ])>
                        Streaming is currently {{ $streamingEnabled ? 'ENABLED' : 'DISABLED' }}
                    </p>
                </div>
            </div>

            <!-- Model Selection -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Streaming AI Model</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Choose which AI model to use for streaming responses:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($availableModels as $key => $name)
                    <button
                        wire:click="updateModel('{{ $key }}')"
                        type="button"
                        @class([ 'p-4 rounded-xl border-2 text-left transition-all duration-150 cursor-pointer' , 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-400'=> $selectedModel === $key,
                        'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500' => $selectedModel !== $key,
                        ])
                        wire:loading.attr="disabled">
                        <div class="flex items-center justify-between">
                            <div>
                                <p @class([ 'font-medium' , 'text-blue-600 dark:text-blue-400'=> $selectedModel === $key,
                                    'text-gray-900 dark:text-white' => $selectedModel !== $key,
                                    ])>
                                    {{ $name }}
                                </p>
                                <p @class([ 'text-xs' , 'text-blue-500 dark:text-blue-400'=> $selectedModel === $key,
                                    'text-gray-500 dark:text-gray-400' => $selectedModel !== $key,
                                    ])>
                                    @if(strpos($key, 'gpt-4o') !== false)
                                    Fastest & Most Accurate
                                    @elseif(strpos($key, 'claude') !== false)
                                    Best for Long Content
                                    @elseif(strpos($key, 'llama') !== false)
                                    Open Source Model
                                    @else
                                    Efficient Option
                                    @endif
                                </p>
                            </div>
                            @if($selectedModel === $key)
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Chunk Delay Setting -->
            <div class="mb-8 pb-8 border-b border-gray-200 dark:border-zinc-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Streaming Speed</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Control how fast characters appear (in milliseconds):</p>

                <div class="space-y-4">
                    <input
                        type="range"
                        min="10"
                        max="1000"
                        step="10"
                        wire:change="updateChunkDelay($event.target.value)"
                        value="{{ $chunkDelay }}"
                        class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-lg appearance-none cursor-pointer accent-blue-600 transition-all" />

                    <div class="grid grid-cols-4 gap-2 text-center text-sm">
                        <button
                            wire:click="updateChunkDelay(10)"
                            type="button"
                            @class([ 'px-3 py-2 rounded-lg border transition-all duration-150' , 'bg-blue-100 dark:bg-blue-900/20 border-blue-500'=> $chunkDelay === 10,
                            'border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' => $chunkDelay !== 10,
                            ])
                            wire:loading.attr="disabled">
                            <span class="font-medium">Super Fast</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">10ms</span>
                        </button>
                        <button
                            wire:click="updateChunkDelay(100)"
                            type="button"
                            @class([ 'px-3 py-2 rounded-lg border transition-all duration-150' , 'bg-blue-100 dark:bg-blue-900/20 border-blue-500'=> $chunkDelay === 100,
                            'border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' => $chunkDelay !== 100,
                            ])
                            wire:loading.attr="disabled">
                            <span class="font-medium">Fast</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">100ms</span>
                        </button>
                        <button
                            wire:click="updateChunkDelay(300)"
                            type="button"
                            @class([ 'px-3 py-2 rounded-lg border transition-all duration-150' , 'bg-blue-100 dark:bg-blue-900/20 border-blue-500'=> $chunkDelay === 300,
                            'border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' => $chunkDelay !== 300,
                            ])
                            wire:loading.attr="disabled">
                            <span class="font-medium">Slow</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">300ms</span>
                        </button>
                        <button
                            wire:click="updateChunkDelay(500)"
                            type="button"
                            @class([ 'px-3 py-2 rounded-lg border transition-all duration-150' , 'bg-blue-100 dark:bg-blue-900/20 border-blue-500'=> $chunkDelay === 500,
                            'border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' => $chunkDelay !== 500,
                            ])
                            wire:loading.attr="disabled">
                            <span class="font-medium">Very Slow</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block">500ms</span>
                        </button>
                    </div>

                    <div class="mt-4 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50">
                        <p class="text-sm text-blue-700 dark:text-blue-400 font-medium">
                            Current speed: <span class="font-bold">{{ $chunkDelay }}ms</span> per character
                        </p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                            Lower values = faster streaming (more CPU usage) | Higher values = smoother experience
                        </p>
                    </div>
                </div>
            </div>

            <!-- Features Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="p-4 rounded-lg bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700/50">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium text-green-900 dark:text-green-200">Real-time Display</p>
                            <p class="text-sm text-green-700 dark:text-green-300 mt-1">Watch AI responses appear character by character</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border border-blue-200 dark:border-blue-700/50">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium text-blue-900 dark:text-blue-200">Customizable Speed</p>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Control how fast responses are displayed</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-700/50">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium text-purple-900 dark:text-purple-200">Model Selection</p>
                            <p class="text-sm text-purple-700 dark:text-purple-300 mt-1">Choose from 8 powerful AI models</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 border border-orange-200 dark:border-orange-700/50">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium text-orange-900 dark:text-orange-200">No Extra Cost</p>
                            <p class="text-sm text-orange-700 dark:text-orange-300 mt-1">Uses the same API credits as normal responses</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button
                    wire:click="testStreaming"
                    type="button"
                    @disabled(!$streamingEnabled)
                    @class([ 'flex-1 px-6 py-3 rounded-xl transition-all duration-150 font-medium inline-flex items-center justify-center gap-2' , 'bg-blue-600 hover:bg-blue-700 text-white'=> $streamingEnabled,
                    'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed' => !$streamingEnabled,
                    ])
                    wire:loading.attr="disabled"
                    wire:target="testStreaming">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Test Streaming
                </button>

                <a href="{{ route('chats.create') }}" class="flex-1 px-6 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white transition-all font-medium text-center inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Try Streaming Chat
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-8 p-6 rounded-xl bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 border border-cyan-200 dark:border-cyan-700/50">
            <h3 class="font-bold text-cyan-900 dark:text-cyan-200 mb-2"> How Message Streaming Works</h3>
            <ul class="space-y-2 text-sm text-cyan-800 dark:text-cyan-300">
                <li class="flex gap-2">
                    <span class="text-cyan-600 dark:text-cyan-400">•</span>
                    <span>When you enable streaming, AI responses will appear character by character in real-time</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-cyan-600 dark:text-cyan-400">•</span>
                    <span>Your selected model will be used for all chats</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-cyan-600 dark:text-cyan-400">•</span>
                    <span>Adjust the speed to your preference - faster for responsive feel, slower for readable experience</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-cyan-600 dark:text-cyan-400">•</span>
                    <span>Streaming works with all compatible AI models</span>
                </li>
            </ul>
        </div>
    </div>
</div>