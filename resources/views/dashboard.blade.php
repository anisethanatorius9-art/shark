<x-layouts.app :title="__('')">
    <div class="min-h-screen relative overflow-hidden">
        <!-- Beautiful Dashboard Background -->
        <div class="fixed inset-0 z-0">
            <!-- Gradient Orbs -->
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-gradient-to-br from-violet-400 via-purple-500 to-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-25 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-72 h-72 bg-gradient-to-br from-amber-400 via-orange-500 to-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 4s;"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 3s;"></div>

            <!-- Subtle Grid Pattern -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>

            <!-- Main Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 p-6 space-y-6">
            <!-- Welcome Header -->
            <div class="bg-white/70 dark:bg-zinc-800/70 backdrop-blur-sm rounded-2xl p-6 border border-white/20 dark:border-zinc-700/50 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Here's what's happening with your chats today.</p>
                    </div>
                    <a href="{{ route('chats.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Chat
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Chats -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-5 border border-gray-200 dark:border-zinc-700 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Chats</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ auth()->user()->chats()->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                </div>



                <!-- Projects -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-5 border border-gray-200 dark:border-zinc-700 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Projects</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ auth()->user()->projects()->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Subscription Status -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl p-5 border border-gray-200 dark:border-zinc-700 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Plan</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ auth()->user()->subscription?->name ?? 'Free' }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('chats.create') }}" class="group bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Start New Chat</h3>
                            <p class="text-blue-100 text-sm">Begin a conversation with AI</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('chats.group.create') }}" class="group relative bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 rounded-xl p-6 text-white hover:from-violet-700 hover:via-purple-700 hover:to-fuchsia-700 transition-all duration-300 shadow-lg hover:shadow-2xl hover:shadow-purple-500/25 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-300"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-400/20 rounded-full blur-2xl group-hover:bg-purple-400/30 transition-all duration-300"></div>
                    <div class="relative flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-inner">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">New Group Chat</h3>
                            <p class="text-purple-100 text-sm">Create a group with friends</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('chats.streaming.settings') }}" class="group bg-gradient-to-br from-cyan-600 to-teal-600 rounded-xl p-6 text-white hover:from-cyan-700 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Message Streaming</h3>
                            <p class="text-cyan-100 text-sm">Enable AI response streaming</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('apps.index') }}" class="group bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white hover:from-green-700 hover:to-green-800 transition-all shadow-lg hover:shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0L12 11 4 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Explore Apps</h3>
                            <p class="text-green-100 text-sm">Discover AI-powered tools</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Chats Section -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Chats</h2>
                    <a href="{{ route('chats.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View all</a>
                </div>

                @if(auth()->user()->chats()->latest()->take(5)->count() > 0)
                <div class="divide-y divide-gray-100 dark:divide-zinc-700">
                    @foreach(auth()->user()->chats()->latest()->take(5)->get() as $chat)
                    <a href="{{ route('chats.show', $chat->uuid) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <div class="w-10 h-10 bg-gray-100 dark:bg-zinc-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $chat->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $chat->messages->count() }} messages</p>
                        </div>
                        <div class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $chat->created_at->diffForHumans() }}
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No chats yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Start your first conversation with SHARK GPT</p>
                    <a href="{{ route('chats.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Chat
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>