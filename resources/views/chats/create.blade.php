<x-layouts.app title="SharkGPT">
    <div class="h-screen flex flex-col bg-gray-50">

        <!-- Header -->
        <header class="flex items-center px-6 py-4">
            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">
                SHARK GPT
            </h1>
        </header>

        <!-- Chat Area -->
        <div id="chat-container" class="flex-1 overflow-y-auto px-4">
            <div class="max-w-3xl mx-auto py-6">

                <!-- Welcome -->
                <div id="welcome-message" class="text-center mt-24">
                    <h2 class="text-3xl font-semibold text-gray-900 mb-3">
                        How can I help you today?
                    </h2>
                    <p class="text-gray-500 mb-8">
                        Ask anything, get answers instantly.
                    </p>

                    <!-- Quick Options -->
                    <div class="grid grid-cols-3 gap-3 max-w-lg mx-auto">
                        <button onclick="selectQuickOption('chatgpt')" class="p-3 bg-white rounded-xl shadow-sm hover:shadow transition text-sm">
                            Chat
                        </button>
                        <button onclick="selectQuickOption('dalle')" class="p-3 bg-white rounded-xl shadow-sm hover:shadow transition text-sm">
                            Image
                        </button>
                        <button onclick="selectQuickOption('search')" class="p-3 bg-white rounded-xl shadow-sm hover:shadow transition text-sm">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Messages -->
                <div id="messages-container" class="space-y-6"></div>

            </div>
        </div>

        <!-- INPUT AREA -->
        <div class="fixed bottom-0 left-0 right-0 px-4 pb-6 bg-gradient-to-t from-white via-white/90 to-transparent">
            <div class="max-w-3xl mx-auto">

                <form id="quick-chat-form" onsubmit="return handleQuickChat(event)">
                    @csrf

                    <!-- Input Box -->
                    <div class="flex items-end gap-3 bg-white rounded-3xl px-4 py-3 shadow-md focus-within:shadow-lg transition">

                        <!-- Plus Button -->
                        <div class="relative">
                            <button type="button" onclick="togglePlusMenu()"
                                class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div id="plus-menu"
                                class="hidden absolute bottom-full mb-2 w-44 bg-white rounded-xl shadow-lg py-2">

                                <button type="button" onclick="triggerFileUpload('image')"
                                    class="w-full px-4 py-2 text-sm hover:bg-gray-100 flex gap-2">
                                    Image
                                </button>

                                <button type="button" onclick="triggerFileUpload('code')"
                                    class="w-full px-4 py-2 text-sm hover:bg-gray-100 flex gap-2">
                                    Code
                                </button>
                            </div>
                        </div>

                        <!-- Textarea -->
                        <textarea id="message-input"
                            name="message"
                            rows="1"
                            placeholder="Message SharkGPT..."
                            class="flex-1 resize-none bg-transparent outline-none text-gray-800 placeholder-gray-400 text-[15px] leading-relaxed max-h-40"
                            oninput="autoResize(this)"
                            onkeydown="handleKeydown(event)"
                            required></textarea>

                        <!-- Voice -->
                        <button type="button" id="voice-button"
                            onclick="toggleRecording()"
                            class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100">

                            <svg id="mic-icon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-gray-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 18.75a6 6 0 006-6v-1.5m-12 0v1.5a6 6 0 006 6m0 0v3" />
                            </svg>

                            <svg id="stop-icon" class="hidden w-6 h-6 text-red-500"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="6" y="6" width="12" height="12" />
                            </svg>
                        </button>

                        <!-- Send -->
                        <button type="submit"
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3 3l18 9-18 9 3-9Z" />
                            </svg>
                        </button>

                    </div>

                    <p class="text-center text-xs text-gray-400 mt-3">
                        SharkGPT can make mistakes.
                    </p>

                </form>
            </div>
        </div>
    </div>

    <script>
        let isSubmitting = false;

        // Auto resize
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Enter send
        function handleKeydown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleQuickChat(e);
            }
        }

        // Send message
        function handleQuickChat(e) {
            e.preventDefault();

            const input = document.getElementById('message-input');
            const message = input.value.trim();

            if (!message || isSubmitting) return false;

            addMessage(message, 'user');
            input.value = '';
            autoResize(input);



            return false;
        }

        // Add message
        function addMessage(text, role) {
            const container = document.getElementById('messages-container');
            document.getElementById('welcome-message').style.display = 'none';

            const isUser = role === 'user';

            const div = document.createElement('div');
            div.className = "flex " + (isUser ? "justify-end" : "");

            div.innerHTML = `
        <div class="${isUser ? 'bg-black text-white' : 'bg-white'} px-4 py-3 rounded-2xl max-w-[80%] text-sm shadow-sm ${isUser ? 'ml-auto' : 'mr-auto'}">
            ${text}
        </div>
    `;

            container.appendChild(div);
            scrollToBottom();
        }

        // Scroll
        function scrollToBottom() {
            const container = document.getElementById('chat-container');
            container.scrollTop = container.scrollHeight;
        }

        // Dropdown
        function togglePlusMenu() {
            document.getElementById('plus-menu').classList.toggle('hidden');
        }

        // Close menu
        document.addEventListener('click', function(e) {
            const btn = document.getElementById('plus-button');
            const menu = document.getElementById('plus-menu');

            if (!btn?.contains(e.target) && !menu?.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        // Quick options
        function selectQuickOption(type) {
            const input = document.getElementById('message-input');

            if (type === 'chatgpt') input.value = "Hello...";
            if (type === 'dalle') input.value = "Create an image of ";
            if (type === 'search') input.value = "Search for ";

            input.focus();
        }
    </script>
</x-layouts.app>
