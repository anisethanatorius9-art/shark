@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'dark' : '' }}">

<head>
    @include('partials.head')
    @auth
    <script>
        document.documentElement.lang = "{{ auth()->user()->language ?? 'en' }}";
    </script>
    @endauth
</head>

<body x-data="{
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        chatsOpen: true,
        projectsOpen: true,
        showSearch: false,
        noTransition: true,
        toggleSidebar() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebarCollapsed', this.collapsed);
        }
    }"
    :class="collapsed ? 'sidebar-collapsed' : ''"
    x-init="setTimeout(() => noTransition = false, 100)"
    :class="noTransition ? 'no-transition' : ''"
    class="min-h-screen bg-white dark:bg-zinc-900 flex">

    <!-- SIDEBAR -->
    <flux:sidebar sticky
        class="transition-all duration-300 ease-in-out w-64 sidebar-collapsed:w-20 border-e border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 sidebar-item">
                <x-app-logo />
                <span class="sidebar-label text-sm font-semibold">{{ $title ?? '' }}</span>
            </a>
            <button @click="toggleSidebar" class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                <svg class="w-5 h-5 transition-transform" :class="collapsed ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- PRIMARY ACTIONS -->
        <div class="px-3 py-2 space-y-1">
            <a href="{{ route('chats.create') }}" wire:navigate
                class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="sidebar-label text-sm" data-lang-key="new_chat">@lang('messages.new_chat')</span>
            </a>

            <button @click="showSearch = !showSearch"
                class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="sidebar-label text-sm" data-lang-key="search">@lang('messages.search')</span>
            </button>

            <div x-show="showSearch" x-transition class="mt-2 px-3">
                <input type="text" placeholder="Search chats..." class="w-full px-3 py-2 text-sm border border-zinc-200 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <a href="{{ route('library.index') }}"
                class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg bg-cyan-50 dark:bg-cyan-900/20 hover:bg-cyan-100 dark:hover:bg-cyan-900/40 transition">
                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20H6a2 2 0 01-2-2V6a2 2 0 012-2h6m0 16h6a2 2 0 002-2V6a2 2 0 00-2-2h-6m0 16V4" />
                </svg>
                <span class="sidebar-label text-sm font-medium text-cyan-700 dark:text-cyan-300" data-lang-key="library">@lang('messages.library')</span>
            </a>

            <a href="{{ route('apps.index') }}"
                class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0v10l-8 4-8-4V7m16 0L12 11 4 7" />
                </svg>
                <span class="sidebar-label text-sm font-medium text-blue-700 dark:text-blue-300" data-lang-key="apps">@lang('messages.apps')</span>
            </a>
        </div>

        <div class="px-3 py-2">
            <div class="h-px bg-zinc-200 dark:bg-zinc-700"></div>
        </div>

        <!-- PROJECTS -->
        <div class="px-3 py-2 flex flex-col">
            <button @click="projectsOpen = !projectsOpen"
                class="flex items-center justify-between w-full px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                <span class="sidebar-label text-xs text-zinc-500" data-lang-key="projects">@lang('messages.projects')</span>
                <svg class="w-3 h-3 text-zinc-500 transition-transform" :class="projectsOpen ? 'rotate-90' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="projectsOpen" x-collapse class="mt-1 space-y-1">
                <a href="{{ route('projects.create') }}"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition text-sm">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="sidebar-label font-medium text-blue-700 dark:text-blue-300" data-lang-key="projects">@lang('messages.projects')</span>
                </a>
                @foreach(auth()->user()->projects()->latest()->take(20)->get() as $project)
                <a href="{{ route('projects.show', $project) }}"
                    class="sidebar-item flex items-center gap-3 px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-zinc-800 transition text-sm">
                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                    </svg>
                    <span class="sidebar-label truncate">{{ $project->title }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- GROUP CHAT ACTION -->
        <div class="px-3 py-3">
            <a href="{{ route('chats.group.create') }}"
                class="sidebar-item flex items-center gap-3 px-4 py-3.5 rounded-xl
                      bg-gradient-to-r from-violet-600 via-purple-600 to-fuchsia-600
                      hover:from-violet-700 hover:via-purple-700 hover:to-fuchsia-700
                      text-white
                      transition-all duration-300 group
                      shadow-lg hover:shadow-xl hover:shadow-purple-500/25
                      transform hover:scale-[1.02]">

                <div class="flex items-center justify-center w-10 h-10 rounded-lg
                            bg-white/20 backdrop-blur-sm
                            group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5V4H2v16h5M9 12h6M12 9v6" />
                    </svg>
                </div>

                <div class="sidebar-label flex flex-col leading-tight">
                    <span class="text-sm font-bold" data-lang-key="new_group_chat">@lang('messages.new_group_chat')</span>
                    <span class="text-xs text-white/70" data-lang-key="chat_with_friends">@lang('messages.chat_with_friends')</span>
                </div>
            </a>
        </div>

        <!-- USER MENU -->
        <div class="mt-auto p-3 border-t border-zinc-200 dark:border-zinc-700" x-data="{ open: false }">
            <div class="relative">
                <button @click="open = !open" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-zinc-500 to-zinc-700 text-white flex items-center justify-center text-sm font-bold shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-left sidebar-label">
                        <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </button>
                <div x-show="open" @click.outside="open=false" x-transition class="absolute bottom-14 left-0 w-52 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl overflow-hidden">
                    <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700" data-lang-key="settings">@lang('messages.settings')</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-sm hover:bg-red-100 dark:hover:bg-red-800" data-lang-key="log_out">@lang('messages.log_out')</button>
                    </form>
                </div>
            </div>
        </div>

    </flux:sidebar>

    <!-- MAIN CONTENT -->
    <main class="flex-1">{{ $slot }}</main>

    @fluxScripts
</body>

</html>