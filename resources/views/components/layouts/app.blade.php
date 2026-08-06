<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @fluxAppearance
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased" x-data="{ sidebarOpen: false, showSearch: false }">
    @auth
    <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700" x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-full" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-full">


        <!-- Projects Group -->
        <flux:sidebar.group expandable heading="Projects" class="grid">
            <flux:sidebar.item icon="plus" href="{{ route('projects.create') }}" wire:navigate>
                New Project
            </flux:sidebar.item>
            @if(auth()->user()->projects()->count() > 0)
            @foreach(auth()->user()->projects()->latest()->take(10)->get() as $project)
            <flux:sidebar.item icon="folder" href="{{ route('projects.show', $project) }}" wire:navigate>
                {{ $project->title }}
            </flux:sidebar.item>
            @endforeach
            @endif
        </flux:sidebar.group>

        <!-- Group Chat -->
        <flux:sidebar.item icon="users" href="{{ route('chats.group.create') }}" wire:navigate>
            New Group Chat
        </flux:sidebar.item>

        <flux:sidebar.spacer />

        <!-- Chats Section -->
        <flux:sidebar.nav>
            <div class="px-3 py-2">
                <div class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Your chats</div>
            </div>
            @if(auth()->user()->chats()->count() > 0)
            @foreach(auth()->user()->chats()->latest()->take(30)->get() as $chat)
            <flux:sidebar.item icon="chat-bubble-left" href="{{ route('chats.show', $chat->uuid) }}" wire:navigate>
                {{ $chat->title }}
            </flux:sidebar.item>
            @endforeach
            @endif
        </flux:sidebar.nav>

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

    <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 sticky top-0 z-50">
        <!-- Sidebar Toggle Button -->
        <flux:sidebar.toggle @click="sidebarOpen = !sidebarOpen" class="lg:hidden" icon="bars-2" inset="left" />

        <!-- App Logo and Brand -->
        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <!-- Main Navigation -->
        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />


        <!-- Model Selector in Header -->
        <flux:navbar class="me-1.5 rtl:space-x-reverse py-0!" x-data="{
                    open: false,
                    selected: 'GPT-3.5',
                    isPremium: {{ auth()->user()->canUseGPT4() ? 'true' : 'false' }},
                    updateModel(model, value) {
                        this.selected = model;
                        this.open = false;
                        // Store selected model in localStorage for quick chat
                        localStorage.setItem('selected_model', value);
                    }
                }">
            <button
                @click="open = !open"
                class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 rounded-lg text-sm font-medium transition-colors border border-gray-200 dark:border-zinc-700">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-green-600 dark:text-green-400 font-medium" x-text="selected"></span>
                <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.outside="open=false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-lg z-50 overflow-hidden">
                <div class="py-1">

                    <!-- Premium models (only for subscribers) -->
                    <template x-if="isPremium">
                        <div>
                            <div class="px-4 py-1.5 text-xs text-gray-500 bg-gray-50 dark:bg-zinc-700 dark:text-gray-400 font-medium">Premium Models</div>
                            <button @click="updateModel('GPT-4o', 'gpt-4o')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                    GPT-4o
                                </span>
                                <span x-show="selected === 'GPT-4o'" class="text-green-500">✓</span>
                            </button>
                            <button @click="updateModel('GPT-4 Turbo', 'gpt-4-turbo')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                    GPT-4 Turbo
                                </span>
                                <span x-show="selected === 'GPT-4 Turbo'" class="text-green-500">✓</span>
                            </button>
                            <button @click="updateModel('GPT-4', 'gpt-4')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                    GPT-4
                                </span>
                                <span x-show="selected === 'GPT-4'" class="text-green-500">✓</span>
                            </button>
                            <div class="border-t border-gray-200 dark:border-zinc-700 my-1"></div>
                        </div>
                    </template>

                    <!-- Free models -->
                    <div class="px-4 py-1.5 text-xs text-gray-500 bg-gray-50 dark:bg-zinc-700 dark:text-gray-400 font-medium">{{ auth()->user()->canUseGPT4() ? 'All Models' : 'Free Models' }}</div>
                    <button @click="updateModel('GPT-3.5', 'gpt-3.5-turbo')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            GPT-3.5 Turbo
                        </span>
                        <span x-show="selected === 'GPT-3.5'" class="text-green-500">✓</span>
                    </button>
                    <button @click="updateModel('Llama 70B', 'llama-3.1-70b')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-orange-500 rounded-full"></span>
                            Llama 3.1 70B
                        </span>
                        <span x-show="selected === 'Llama 70B'" class="text-green-500">✓</span>
                    </button>
                    <button @click="updateModel('Mistral 7B', 'mistral-7b')" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-zinc-700 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            Mistral 7B
                        </span>
                        <span x-show="selected === 'Mistral 7B'" class="text-green-500">✓</span>
                    </button>

                    <!-- Upgrade link for free users -->
                    <template x-if="!isPremium">
                        <div class="border-t border-gray-200 dark:border-zinc-700 mt-1 pt-1">
                            <a href="{{ route('subscription.pricing') }}" class="block px-4 py-2 text-sm text-center bg-gradient-to-r from-violet-600 to-purple-600 text-white hover:from-violet-700 hover:to-purple-700">
                                Upgrade to Premium
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </flux:navbar>

        <!-- Language Selector -->
        <flux:navbar class="p-0 text-sm font-normal">
            <x-language-selector />
        </flux:navbar>

        <!-- Search Button -->
        <flux:navbar class="p-0 text-sm font-normal">
            <flux:navbar.item icon="magnifying-glass" href="#" @click="showSearch = !showSearch" label="Search" />
        </flux:navbar>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile
                class="cursor-pointer"
                :initials="auth()->user()->initials()" />

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
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

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