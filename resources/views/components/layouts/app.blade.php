<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @fluxAppearance
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === null ? window.innerWidth >= 1024 : JSON.parse(localStorage.getItem('sidebarOpen')), showSearch: false }">
    @auth
    <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-full" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-full">
        <flux:sidebar.header>
            <div class="flex items-center gap-3">
                <x-app-logo-icon class="size-8" />
                <div>
                    <div class="text-sm font-semibold" data-lang-key="app_name">SHARK GPT</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400" data-lang-key="sidebar_dashboard">Sidebar Dashboard</div>
                </div>
            </div>
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.item icon="home" href="{{ route('dashboard') }}" wire:navigate>
            <span data-lang-key="dashboard">@lang('messages.dashboard')</span>
        </flux:sidebar.item>

        <flux:sidebar.item icon="chat-bubble-left" href="{{ route('chats.create') }}" wire:navigate>
            <span data-lang-key="new_chat">@lang('messages.new_chat')</span>
        </flux:sidebar.item>

        <flux:sidebar.item icon="users" href="{{ route('chats.group.create') }}" wire:navigate>
            <span data-lang-key="new_group_chat">@lang('messages.new_group_chat')</span>
        </flux:sidebar.item>

        <flux:sidebar.item icon="bolt" href="{{ route('chats.streaming.settings') }}" wire:navigate>
            <span data-lang-key="message_streaming">@lang('messages.message_streaming')</span>
        </flux:sidebar.item>

        <flux:sidebar.item icon="cube" href="{{ route('apps.index') }}" wire:navigate>
            <span data-lang-key="explore_apps">@lang('messages.explore_apps')</span>
        </flux:sidebar.item>

        <flux:sidebar.spacer />

        <!-- Projects Group -->
        <flux:sidebar.group expandable :heading="__('messages.projects')" class="grid">
            <flux:sidebar.item icon="plus" href="{{ route('projects.create') }}" wire:navigate>
                <span data-lang-key="projects">@lang('messages.projects')</span>
            </flux:sidebar.item>
            @if(auth()->user()->projects()->count() > 0)
            @foreach(auth()->user()->projects()->latest()->take(10)->get() as $project)
            <flux:sidebar.item icon="folder" href="{{ route('projects.show', $project) }}" wire:navigate>
                {{ $project->title }}
            </flux:sidebar.item>
            @endforeach
            @endif
        </flux:sidebar.group>

        <flux:sidebar.group expandable :heading="__('messages.recent_chats')" class="grid">
            @if(auth()->user()->chats()->count() > 0)
            @foreach(auth()->user()->chats()->latest()->take(30)->get() as $chat)
            <flux:sidebar.item icon="chat-bubble-left" href="{{ route('chats.show', $chat->uuid) }}" wire:navigate>
                {{ $chat->title }}
            </flux:sidebar.item>
            @endforeach
            @else
            <flux:sidebar.item icon="sparkles" as="span" class="opacity-70">
                @lang('messages.no_recent_chats')
            </flux:sidebar.item>
            @endif
        </flux:sidebar.group>

        <!-- User Profile -->
        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile
                :initials="auth()->user()->initials()"
                :name="auth()->user()->name" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item icon="cog" href="{{ route('settings.profile') }}" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Log Out
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <div class="fixed top-4 left-4 z-50">
        <button type="button" @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-200 shadow-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" aria-label="Toggle sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div class="fixed top-4 right-4 z-50 flex flex-col items-end gap-3">
        <a href="{{ route('subscription.pricing') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-violet-600 to-purple-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-violet-500/20 hover:opacity-95 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m4-4H8m10 4a8 8 0 11-16 0 8 8 0 0116 0z" />
            </svg>
            @lang('messages.subscription')
        </a>
        <div class="bg-white/90 dark:bg-zinc-900/90 border border-zinc-200 dark:border-zinc-700 rounded-full shadow-sm p-1">
            <x-language-selector />
        </div>
    </div>

    <!-- Search Modal -->
    <div x-show="showSearch" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-start justify-center pt-20" @click="showSearch = false">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-2xl w-full mx-4" @click.stop>
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search chats, projects, or content..." class="flex-1 bg-transparent border-0 outline-none text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 dark:placeholder-zinc-400" autofocus>
                    <button @click="showSearch = false" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">Recent searches</div>
                <div class="space-y-2">
                    <!-- Recent search results would go here -->
                    <div class="text-sm text-zinc-400 dark:text-zinc-500 italic">No recent searches</div>
                </div>
            </div>
        </div>
    </div>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @endauth

    @livewireScripts
    @fluxScripts
</body>

</html>