<div class="flex flex-col min-h-screen">
    <flux:main class="relative overflow-hidden min-h-screen flex-1">
        <div class="fixed inset-0 z-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full bg-gradient-to-br from-green-400 via-emerald-500 to-teal-600 mix-blend-multiply blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute top-1/4 right-1/4 w-80 h-80 rounded-full bg-gradient-to-br from-violet-400 via-purple-500 to-indigo-600 mix-blend-multiply blur-3xl opacity-25 animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-1/4 left-1/3 w-72 h-72 rounded-full bg-gradient-to-br from-amber-400 via-orange-500 to-red-500 mix-blend-multiply blur-3xl opacity-20 animate-pulse" style="animation-delay: 4s;"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 mix-blend-multiply blur-3xl opacity-20 animate-pulse" style="animation-delay: 3s;"></div>
            <div class="absolute inset-0 bg-[linear-gradient(rgba(0,0,0,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,0,0,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900"></div>
        </div>

        <div class="relative z-10 p-6 space-y-6">
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-sm rounded-3xl border border-white/20 dark:border-zinc-700/50 shadow-xl">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between p-6">
                    <div class="space-y-3">
                        <flux:heading size="xl">Welcome back, {{ auth()->user()->name }}!</flux:heading>
                        <flux:subheading>Here's what's happening with your chats today.</flux:subheading>
                    </div>
                    <a href="{{ route('chats.create') }}" wire:navigate class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Chat
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Chats</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalChats }}</p>
                        </div>
                        <flux:badge color="blue">Live</flux:badge>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Chats created across all conversations.</p>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Messages</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalMessages }}</p>
                        </div>
                        <flux:badge color="purple">Total</flux:badge>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Total message count across your chats.</p>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Projects</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalProjects }}</p>
                        </div>
                        <flux:badge color="green">Active</flux:badge>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Projects saved for your AI workflows.</p>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Plan</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $userPlan }}</p>
                        </div>
                        <flux:badge color="orange">Status</flux:badge>
                    </div>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Your current subscription tier.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <flux:heading size="lg">Recent Chats</flux:heading>
                        <flux:button variant="ghost" size="sm" icon="plus" href="{{ route('chats.create') }}" wire:navigate>
                            New Chat
                        </flux:button>
                    </div>
                    <div class="space-y-2">
                        @if(count($recentChats) > 0)
                        @foreach($recentChats as $chat)
                        <a href="{{ route('chats.show', $chat['uuid']) }}" wire:navigate class="flex items-center justify-between p-4 rounded-2xl border border-transparent hover:bg-gray-50 dark:hover:bg-zinc-800 hover:border-gray-200 dark:hover:border-zinc-700 transition group">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $chat['name'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $chat['message_count'] }} messages</p>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ms-4 flex-shrink-0">{{ $chat['updated_at'] }}</span>
                        </a>
                        @endforeach
                        @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p class="mb-2">No chats yet</p>
                            <flux:button variant="filled" size="sm" icon="plus" href="{{ route('chats.create') }}" wire:navigate>
                                Create Chat
                            </flux:button>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 rounded-3xl border border-gray-200 dark:border-zinc-700 shadow-sm p-6">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <flux:heading size="lg">Recent Projects</flux:heading>
                        <flux:button variant="ghost" size="sm" icon="plus" href="{{ route('projects.create') }}" wire:navigate>
                            New Project
                        </flux:button>
                    </div>
                    <div class="space-y-2">
                        @if(count($recentProjects) > 0)
                        @foreach($recentProjects as $project)
                        <a href="{{ route('projects.show', $project['id']) }}" wire:navigate class="flex items-center justify-between p-4 rounded-2xl border border-transparent hover:bg-gray-50 dark:hover:bg-zinc-800 hover:border-gray-200 dark:hover:border-zinc-700 transition group">
                            <p class="font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $project['name'] }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ms-4 flex-shrink-0">{{ $project['updated_at'] }}</span>
                        </a>
                        @endforeach
                        @else
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p class="mb-2">No projects yet</p>
                            <flux:button variant="filled" size="sm" icon="plus" href="{{ route('projects.create') }}" wire:navigate>
                                Create Project
                            </flux:button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <flux:button variant="filled" icon="plus" href="{{ route('chats.create') }}" wire:navigate class="justify-start gap-3 py-6">
                    <div>
                        <p class="font-semibold text-left">New Chat</p>
                        <p class="text-sm text-white/80">Start a conversation</p>
                    </div>
                </flux:button>
                <flux:button variant="filled" icon="folder-plus" href="{{ route('projects.create') }}" wire:navigate class="justify-start gap-3 py-6 bg-gradient-to-br from-purple-500 to-purple-600">
                    <div>
                        <p class="font-semibold text-left">New Project</p>
                        <p class="text-sm text-white/80">Create a project</p>
                    </div>
                </flux:button>
                <flux:button variant="filled" icon="bolt" href="{{ route('chats.streaming.settings') }}" wire:navigate class="justify-start gap-3 py-6 bg-gradient-to-br from-cyan-500 to-teal-600">
                    <div>
                        <p class="font-semibold text-left">Message Streaming</p>
                        <p class="text-sm text-white/80">Enable AI streaming</p>
                    </div>
                </flux:button>
                <flux:button variant="filled" icon="squares-2x2" href="{{ route('apps.index') }}" wire:navigate class="justify-start gap-3 py-6 bg-gradient-to-br from-green-500 to-green-600">
                    <div>
                        <p class="font-semibold text-left">Explore Apps</p>
                        <p class="text-sm text-white/80">Discover AI tools</p>
                    </div>
                </flux:button>
            </div>
        </div>
    </flux:main>
</div>