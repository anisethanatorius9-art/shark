<div class="flex h-screen flex-col transition-colors duration-200" x-data="{ darkMode: true, showModelSelector: false }" :class="darkMode ? 'bg-[#171717] text-white' : 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900'">
    <!-- Header -->
    <div :class="darkMode ? 'border-[#2f2f2f] bg-[#171717]' : 'border-gray-200 bg-white'" class="border-b px-6 py-4">
        <div class="flex max-w-6xl mx-auto items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold">{{ $chat->title }}</h1>
                <p :class="darkMode ? 'text-[#8e8e8e]' : 'text-gray-500'" class="text-xs mt-1">
                    {{ $chat->messages()->count() }} messages • {{ $chat->created_at->diffForHumans() }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Model Selector -->
                <div class="relative">
                    <button @click="showModelSelector = !showModelSelector" :class="darkMode ? 'bg-[#2f2f2f] text-white hover:bg-[#3a3a3a]' : 'bg-gray-200 text-gray-900 hover:bg-gray-300'" class="px-4 py-2 rounded-lg transition text-sm font-medium">
                        🤖 {{ $selectedModel }}
                    </button>
                    <div x-show="showModelSelector" @click.outside="showModelSelector = false" :class="darkMode ? 'bg-[#2f2f2f] border-[#3a3a3a]' : 'bg-white border-gray-300'" class="absolute right-0 mt-2 w-48 border rounded-lg shadow-lg z-50 max-h-60 overflow-y-auto">
                        @php $models = \App\Services\AIService::MODELS; @endphp
                        @foreach($models as $modelKey => $modelName)
                        <button type="button" wire:click="setSelectedModel('{{ $modelKey }}')" @click="showModelSelector = false" :class="darkMode && '{{ $modelKey }}' === '{{ $selectedModel }}' ? 'bg-[#5436da]' : ''" class="w-full text-left px-4 py-2 hover:opacity-75 transition text-sm">
                            ✓ {{ $modelName }} ({{ $modelKey }})
                        </button>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('chats.create') }}" :class="darkMode ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white'" class="flex items-center gap-2 px-4 py-2 rounded-lg transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>New</span>
                </a>
                <button @click="darkMode = !darkMode" :class="darkMode ? 'bg-[#2f2f2f] text-yellow-400' : 'bg-gray-200 text-gray-600'" class="p-2 rounded-lg transition">
                    <svg x-show="darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 1.636l.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto" @load="$dispatch('refresh-messages')" wire:poll-500ms="refreshMessages">
        <div class="max-w-4xl mx-auto px-6 py-6">
            @if(empty($messages))
            <div class="flex flex-col items-center justify-center min-h-[60vh]">
                <div :class="darkMode ? 'bg-gradient-to-br from-green-400 to-blue-500' : 'bg-gradient-to-br from-blue-400 to-blue-600'" class="w-16 h-16 mb-6 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold mb-2">How can I help you today?</h2>
                <p :class="darkMode ? 'text-[#8e8e8e]' : 'text-gray-600'" class="text-center max-w-md">
                    Start a conversation with {{ $selectedModel }}
                </p>
            </div>
            @else
            <div class="space-y-4">
                @foreach($messages as $message)
                <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }} group" x-data="{ showActions: false }">
                    <div class="flex flex-col {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <!-- Message Bubble -->
                        <div :class="{
                            'max-w-2xl px-5 py-3 rounded-3xl text-[15px] leading-7 whitespace-pre-wrap break-words': true,
                            'bg-blue-600 text-white rounded-br-none': '{{ $message['role'] }}' === 'user',
                            'bg-[#2f2f2f] text-[#ececf1] rounded-bl-none': '{{ $message['role'] }}' !== 'user' && darkMode,
                            'bg-gray-100 text-gray-900 rounded-bl-none': '{{ $message['role'] }}' !== 'user' && !darkMode
                        }" @mouseover="showActions = true" @mouseleave="showActions = false" class="shadow-sm hover:shadow-md transition">
                            {{ $message['content'] }}
                        </div>

                        <!-- Message Metadata (Time + Status) -->
                        <div class="flex items-center gap-2 mt-1 px-2">
                            <span :class="darkMode ? 'text-[#8e8e8e]' : 'text-gray-500'" class="text-xs">
                                {{ $message['created_at'] }}
                            </span>

                            @if($message['role'] === 'user')
                            @if($message['status'] === 'read')
                            <span title="Read" class="text-blue-600 flex gap-0">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                                <svg class="w-3 h-3 -ml-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            @elseif($message['status'] === 'delivered')
                            <span title="Delivered" class="text-gray-500 flex gap-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg class="w-3 h-3 -ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            @else
                            <span title="Sent" class="text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            @endif
                            @endif
                        </div>

                        <!-- Message Actions -->
                        <div x-show="showActions" class="flex gap-2 mt-2 opacity-0 group-hover:opacity-100 transition">
                            <button @click="navigator.clipboard.writeText({{ json_encode($message['content']) }})" :class="darkMode ? 'bg-[#2f2f2f] hover:bg-[#3a3a3a] text-[#ececf1]' : 'bg-gray-200 hover:bg-gray-300 text-gray-900'" class="p-2 rounded-lg transition text-xs" title="Copy">
                                📋
                            </button>
                            @if($message['role'] === 'user')
                            <button wire:click="deleteMessage({{ $message['id'] }})" :class="darkMode ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-red-500 hover:bg-red-600 text-white'" class="p-2 rounded-lg transition text-xs" title="Delete" onclick="return confirm('Delete this message?')">
                                🗑️
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Typing Indicator -->
                @if($isLoading || $showTypingIndicator)
                <div class="flex justify-start">
                    <div class="flex items-center gap-2 px-5 py-3 rounded-3xl rounded-bl-none" :class="darkMode ? 'bg-[#2f2f2f]' : 'bg-gray-100'">
                        <span :class="darkMode ? 'text-[#8e8e8e]' : 'text-gray-400'" class="text-xs">
                            {{ $showTypingIndicator ? 'Someone is typing' : 'AI is thinking' }}
                        </span>
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Input Area -->
    <div :class="darkMode ? 'border-[#2f2f2f] bg-[#171717]' : 'border-gray-200 bg-white'" class="border-t px-6 py-6">
        <div class="max-w-4xl mx-auto">
            <form wire:submit.prevent="sendMessage" class="space-y-3">
                <div class="relative">
                    <textarea
                        wire:model="messageText"
                        @focus="$dispatch('user-typing', {{ auth()->id() }})"
                        @blur="$dispatch('user-stopped-typing', {{ auth()->id() }})"
                        wire:keydown.enter.prevent="sendMessage"
                        placeholder="Message SharkGPT..."
                        :class="darkMode ? 'bg-[#2f2f2f] text-[#ececf1] placeholder-[#8e8e8e] focus:ring-[#5436da]' : 'bg-gray-100 text-gray-900 placeholder-gray-500 focus:ring-blue-500'"
                        class="w-full rounded-xl px-4 py-3 pr-12 text-sm border focus:outline-none focus:ring-1 resize-none transition"
                        rows="1"
                        style="min-height: 56px; max-height: 200px;"
                        :class="darkMode ? 'border-[#2f2f2f]' : 'border-gray-300'"></textarea>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        :class="darkMode ? 'bg-[#5436da] hover:bg-[#6338e0] disabled:bg-[#4a2d99]' : 'bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400'"
                        class="absolute right-2 bottom-2 p-2 rounded-lg text-white disabled:cursor-not-allowed transition shadow-lg">
                        @if($isLoading)
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        @endif
                    </button>
                </div>
                @error('messageText')
                <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </form>
            <p :class="darkMode ? 'text-[#8e8e8e]' : 'text-gray-500'" class="text-center text-xs mt-2">
                ℹ️ AI can make mistakes. Please verify important information.
            </p>
        </div>
    </div>

    <style>
        textarea {
            field-sizing: content;
        }

        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: transparent;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #4a4a4a;
            border-radius: 4px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #5a5a5a;
        }
    </style>

    <script>
        // Scroll to bottom on new messages
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.querySelector('.overflow-y-auto');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });

        // Listen for Livewire updates to scroll to bottom
        document.addEventListener('livewire:updated', function() {
            const messagesContainer = document.querySelector('.overflow-y-auto');
            if (messagesContainer) {
                setTimeout(() => {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }, 100);
            }
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</div>