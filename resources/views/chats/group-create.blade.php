<x-layouts.app :title="__('Create Group Chat')">

    <div class="min-h-screen bg-gradient-to-br from-violet-50 via-purple-50 to-fuchsia-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 py-10">
        <div class="max-w-2xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-violet-600 to-purple-600 rounded-2xl shadow-lg shadow-purple-500/25 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Create Group Chat</h1>
                <p class="text-gray-600 dark:text-gray-400">Start a new group conversation with your friends</p>
            </div>

            <div class="bg-white dark:bg-zinc-800 p-8 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-zinc-700">
                <form method="POST" action="{{ route('chats.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Group Name</label>
                        <input type="text" name="title" placeholder="Enter group name"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Select Friends</label>
                        <div class="space-y-2 max-h-96 overflow-y-auto rounded-xl border border-gray-200 dark:border-zinc-600 p-4 bg-gray-50 dark:bg-zinc-700/50">
                            @forelse(auth()->user()->friends ?? [] as $friend)
                            <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-white dark:hover:bg-zinc-600 transition cursor-pointer">
                                <input type="checkbox" name="friends[]" value="{{ $friend->id }}" class="rounded text-purple-600 focus:ring-purple-500" />
                                <div class="flex items-center gap-3">
                                    @if($friend->avatar)
                                    <img src="{{ asset('storage/' . $friend->avatar) }}" alt="{{ $friend->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-purple-500/20">
                                    @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center text-xs font-semibold text-white">{{ strtoupper(substr($friend->name, 0, 1)) }}</div>
                                    @endif
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $friend->name }}</span>
                                </div>
                            </label>
                            @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No friends available. Add friends to create a group.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-semibold rounded-xl hover:from-violet-700 hover:to-purple-700 transition-all shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Group
                        </button>
                        <a href="{{ route('chats.create') }}" class="px-6 py-3 border border-gray-200 dark:border-zinc-600 rounded-xl text-sm font-medium hover:bg-gray-50 dark:hover:bg-zinc-700 transition">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-layouts.app>