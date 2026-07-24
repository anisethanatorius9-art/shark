<div class="flex h-screen flex-col transition-colors duration-200" x-data="{ darkMode: true }" :class="darkMode ? 'bg-[#171717] text-white' : 'bg-gradient-to-br from-gray-50 to-gray-100 text-gray-900'">
    <!-- Header -->
    <div :class="darkMode ? 'border-[#2f2f2f] bg-[#171717]' : 'border-gray-200 bg-white'" class="border-b px-6 py-4">
        <div class="flex max-w-4xl mx-auto items-center justify-between">
            <h1 class="text-xl font-semibold">{{ $chat->title }}</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('chats.create') }}" :class="darkMode ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white'" class="flex items-center gap-2 px-4 py-2 rounded-lg transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span data-lang-key="new_chat">@lang('messages.new_chat')</span>
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
    <div class="flex-1 overflow-y-auto">
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

                </p>
            </div>
            @else
            <div class="space-y-4">
                @foreach($messages as $message)
                <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div :class="{
                        'max-w-xl px-5 py-3 rounded-3xl text-[15px] leading-7 whitespace-pre-wrap': true,
                        'bg-blue-600 text-white rounded-3xl shadow-md': '{{ $message['role'] }}' === 'user',
                        'bg-[#2f2f2f] text-[#ececf1]': '{{ $message['role'] }}' !== 'user' && darkMode,
                        'bg-gray-100 text-gray-900': '{{ $message['role'] }}' !== 'user' && !darkMode
                    }">
                        {{ $message['content'] }}
                    </div>
                </div>
                @endforeach

                @if($isLoading)
                <div class="flex justify-start">
                    <div class="flex items-center gap-2 px-5 py-3 rounded-3xl" :class="darkMode ? 'bg-[#2f2f2f]' : 'bg-gray-100'">
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                        <div :class="darkMode ? 'bg-[#8e8e8e]' : 'bg-gray-400'" class="w-2 h-2 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Input Area -->
        <div :class="darkMode ? 'border-[#2f2f2f] bg-[#171717]' : 'border-gray-200 bg-white'" class="border-t px-6 py-6">
            <div class="max-w-4xl mx-auto">
                <form wire:submit.prevent="sendMessage" class="space-y-3">
                    <div class="relative">
                        <textarea
                            wire:model="messageText"
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
                    AI can make mistakes. Please verify important information.
                </p>
            </div>
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
        // Load selected model from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const selectedModel = localStorage.getItem('selected_model') || 'gpt-4-turbo';
            Livewire.find('{{ $this->id }}').set('selectedModel', selectedModel);
        });
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
