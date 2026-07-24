<x-layouts.app :title="$chat->title">

    <div class="min-h-screen flex flex-col bg-gradient-to-b from-gray-50 to-white">

        <!-- Chat Header - Stable sticky header when scrolling -->
        <div class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 px-4 py-3 shadow-sm">
            <div class="max-w-3xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="font-semibold text-gray-900 truncate max-w-[200px] sm:max-w-none">{{ $chat->title }}</h1>
                        <p class="text-xs text-gray-500">Ask me anything</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Model Selector -->
                    <div x-data="{ open: false, selected: 'GPT-4o' }" class="relative">
                        <button
                            @click="open = !open"
                            class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg text-sm font-medium transition-colors">
                            <span class="text-green-600" x-text="selected"></span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open=false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg z-50 overflow-hidden">
                            <div class="py-1">
                                <button @click="selected = 'GPT-4o'; open = false; updateModel('gpt-4o')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>GPT-4o</span>
                                    <span x-show="selected === 'GPT-4o'" class="text-green-500">✓</span>
                                </button>
                                <button @click="selected = 'GPT-4 Turbo'; open = false; updateModel('gpt-4-turbo')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>GPT-4 Turbo</span>
                                    <span x-show="selected === 'GPT-4 Turbo'" class="text-green-500">✓</span>
                                </button>
                                <button @click="selected = 'GPT-4'; open = false; updateModel('gpt-4')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>GPT-4</span>
                                    <span x-show="selected === 'GPT-4'" class="text-green-500">✓</span>
                                </button>
                                <button @click="selected = 'GPT-3.5 Turbo'; open = false; updateModel('gpt-3.5-turbo')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>GPT-3.5 Turbo</span>
                                    <span x-show="selected === 'GPT-3.5 Turbo'" class="text-green-500">✓</span>
                                </button>
                                <div class="border-t border-gray-200 dark:border-zinc-700 my-1"></div>
                                <button @click="selected = 'Claude 3 Opus'; open = false; updateModel('claude-3-opus')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>Claude 3 Opus</span>
                                    <span x-show="selected === 'Claude 3 Opus'" class="text-green-500">✓</span>
                                </button>
                                <button @click="selected = 'Claude 3 Sonnet'; open = false; updateModel('claude-3-sonnet')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>Claude 3 Sonnet</span>
                                    <span x-show="selected === 'Claude 3 Sonnet'" class="text-green-500">✓</span>
                                </button>
                                <div class="border-t border-gray-200 dark:border-zinc-700 my-1"></div>
                                <button @click="selected = 'Llama 3.1 70B'; open = false; updateModel('llama-3.1-70b')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>Llama 3.1 70B</span>
                                    <span x-show="selected === 'Llama 3.1 70B'" class="text-green-500">✓</span>
                                </button>
                                <button @click="selected = 'Mistral 7B'; open = false; updateModel('mistral-7b')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center justify-between">
                                    <span>Mistral 7B</span>
                                    <span x-show="selected === 'Mistral 7B'" class="text-green-500">✓</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button onclick="document.getElementById('rename-modal-{{ $chat->id }}').showModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors" title="Rename chat">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 overflow-y-auto">
            <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">

                <!-- Empty State -->
                @if($chat->messages->isEmpty())
                <div class="flex flex-col items-center justify-center py-20">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">How can I help you today?</h2>
                    <p class="text-gray-500 text-center max-w-md">
                        I can help you write code, answer questions, or have a conversation. Just type your message below!
                    </p>

                    <!-- Quick suggestions -->
                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button onclick="setPrompt('Help me write a Python function')" class="p-4 bg-white border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-md transition-all text-left group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Write code</span>
                            </div>
                        </button>
                        <button onclick="setPrompt('Explain how machine learning works')" class="p-4 bg-white border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-md transition-all text-left group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Explain concepts</span>
                            </div>
                        </button>
                        <button onclick="setPrompt('Help me write a creative story')" class="p-4 bg-white border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-md transition-all text-left group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Write something</span>
                            </div>
                        </button>
                        <button onclick="setPrompt('What is the weather like today?')" class="p-4 bg-white border border-gray-200 rounded-xl hover:border-green-500 hover:shadow-md transition-all text-left group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center group-hover:bg-teal-200 transition-colors">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Get information</span>
                            </div>
                        </button>
                    </div>
                </div>
                @endif

                <!-- Messages -->
                @if($chat->messages->isNotEmpty())
                @foreach($chat->messages as $message)
                @php
                $isAssistant = ($message->role ?? 'user') === 'assistant';
                @endphp

                <div class="group flex {{ $isAssistant ? 'justify-start' : 'justify-end' }} animate-fade-in">
                    <!-- Message Bubble (no avatar) -->
                    <div class="max-w-[85%] {{ $isAssistant ? '' : '' }}">
                        <div class="{{ $isAssistant ? 'bg-white border border-gray-200' : 'bg-gray-900 text-white' }} rounded-2xl px-4 py-3 shadow-sm">
                            <div class="prose prose-sm max-w-none {{ $isAssistant ? 'prose-gray' : 'prose-invert' }}">
                                <p class="whitespace-pre-line">{{ $message->content }}</p>
                            </div>
                            <div class="flex items-center justify-end gap-2 mt-2 {{ $isAssistant ? 'text-gray-400' : 'text-gray-400' }}">
                                <span class="text-xs">{{ $message->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

            </div>
        </div>

        <!-- Input Area -->
        <div class="sticky bottom-0 bg-white/80 backdrop-blur-sm border-t border-gray-200 px-4 py-4">
            <div class="max-w-3xl mx-auto">
                <form
                    method="POST"
                    action="{{ route('messages.store', $chat) }}"
                    id="chat-form"
                    class="relative"
                    onsubmit="return handleSubmit(event)">
                    @csrf

                    <!-- Hidden input for selected model -->
                    <input type="hidden" id="model-input" name="model" value="gpt-4o">

                    <div class="relative flex items-end gap-2 bg-gray-100 border border-gray-200 rounded-2xl px-4 py-3 focus-within:border-green-500 focus-within:ring-2 focus-within:ring-green-100 transition-all shadow-sm">
                        <textarea
                            id="message-input"
                            name="content"
                            rows="1"
                            placeholder="Ask me anything..."
                            class="flex-1 resize-none border-0 focus:ring-0 outline-none text-gray-800 bg-transparent max-h-32 min-h-[24px]"
                            oninput="autoResize(this)"
                            onkeydown="handleKeydown(event)"
                            required></textarea>

                        <button
                            type="submit"
                            id="send-button"
                            class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-center text-xs text-gray-400 mt-2">
                        AI can make mistakes. Please verify important information.
                    </p>
                </form>
            </div>
        </div>

    </div>

    <script>
        let isSubmitting = false;
        let currentModel = 'gpt-4o';

        function updateModel(model) {
            currentModel = model;
            document.getElementById('model-input').value = model;
        }

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
        }

        function handleKeydown(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                if (document.getElementById('message-input').value.trim() && !isSubmitting) {
                    handleSubmit(event);
                }
            }
        }

        function handleSubmit(event) {
            event.preventDefault();

            const input = document.getElementById('message-input');
            const button = document.getElementById('send-button');
            const message = input.value.trim();

            if (!message || isSubmitting) {
                return false;
            }

            isSubmitting = true;
            button.disabled = true;
            input.disabled = true;

            // Show typing indicator
            showTypingIndicator();

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                document.querySelector('input[name="_token"]')?.value;

            // Send message via fetch (AJAX)
            const form = document.getElementById('chat-form');
            const actionUrl = form.action;

            fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: '_token=' + encodeURIComponent(csrfToken) + '&content=' + encodeURIComponent(message) + '&model=' + encodeURIComponent(currentModel)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server error: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide typing indicator
                    hideTypingIndicator();

                    // Add user's message to chat
                    addMessageToChat(message, 'user');

                    // Add AI response to chat
                    if (data.ai_response) {
                        addMessageToChat(data.ai_response, 'assistant');
                    } else if (data.error) {
                        // Show error message
                        addMessageToChat('Sorry, I encountered an error: ' + data.error, 'assistant');
                    } else {
                        // Fallback if no response
                        addMessageToChat('I\'m having trouble connecting right now. Please try again.', 'assistant');
                    }

                    // Clear input
                    input.value = '';
                    autoResize(input);
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideTypingIndicator();
                    // Show error in chat
                    addMessageToChat('Sorry, an error occurred: ' + error.message + '. Please try again.', 'assistant');
                })
                .finally(() => {
                    isSubmitting = false;
                    button.disabled = false;
                    input.disabled = false;
                    input.focus();
                });

            return false;
        }

        function showTypingIndicator() {
            const messagesContainer = document.querySelector('#chat-messages .max-w-3xl');
            if (!messagesContainer) return;

            // Remove empty state if exists
            const emptyState = messagesContainer.querySelector('.flex-col.items-center.justify-center');
            if (emptyState) {
                emptyState.remove();
            }

            // Create typing indicator
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typing-indicator';
            typingDiv.className = 'flex justify-start animate-fade-in';
            typingDiv.innerHTML = `
                <div class="max-w-[85%]">
                    <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-2xl px-5 py-4 shadow-md">
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1">
                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-bounce"></span>
                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
                                <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                            </div>
                            <span class="text-sm font-medium text-gray-600 ml-2">Thinking...</span>
                        </div>
                    </div>
                </div>
            `;

            messagesContainer.appendChild(typingDiv);
            scrollToBottom();
        }

        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        function addMessageToChat(content, role) {
            const messagesContainer = document.querySelector('#chat-messages .max-w-3xl');
            if (!messagesContainer) return;

            // Remove empty state if exists
            const emptyState = messagesContainer.querySelector('.flex-col.items-center.justify-center');
            if (emptyState) {
                emptyState.remove();
            }

            const isAssistant = role === 'assistant';
            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

            const messageDiv = document.createElement('div');
            messageDiv.className = 'group flex ' + (isAssistant ? 'justify-start' : 'justify-end') + ' animate-fade-in';
            messageDiv.innerHTML = `
                <div class="max-w-[85%] ${isAssistant ? '' : ''}">
                    <div class="${isAssistant ? 'bg-white border border-gray-200' : 'bg-gray-900 text-white'} rounded-2xl px-4 py-3 shadow-sm">
                        <div class="prose prose-sm max-w-none ${isAssistant ? 'prose-gray' : 'prose-invert'}">
                            <p class="whitespace-pre-line">${escapeHtml(content)}</p>
                        </div>
                        <div class="flex items-center justify-end gap-2 mt-2 ${isAssistant ? 'text-gray-400' : 'text-gray-400'}">
                            <span class="text-xs">${timeString}</span>
                        </div>
                    </div>
                </div>
            `;

            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function scrollToBottom() {
            const messagesContainer = document.getElementById('chat-messages');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }

        function setPrompt(prompt) {
            const input = document.getElementById('message-input');
            input.value = prompt;
            input.focus();
            autoResize(input);
        }

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chat-messages');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        .prose {
            font-size: 0.9375rem;
            line-height: 1.625;
        }

        .prose p {
            margin-bottom: 0.75em;
        }

        .prose p:last-child {
            margin-bottom: 0;
        }

        /* Typing indicator animation */
        @keyframes bounce {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-4px);
            }
        }

        .animate-bounce {
            animation: bounce 1s infinite;
        }
    </style>

</x-layouts.app>