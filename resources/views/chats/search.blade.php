<x-layouts.app :title="__('Search chats')">

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-3xl mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-4">Search your chats</h1>

            <form method="GET" action="{{ route('chats.search') }}" class="mb-6">
                <div class="flex items-center gap-2">
                    <input name="q" value="{{ $q ?? '' }}" placeholder="Search messages or chat titles" class="flex-1 px-4 py-2 rounded-md border border-gray-200 focus:ring-black focus:border-black" />
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md">Search</button>
                </div>
            </form>

            @if(isset($q) && $q)
            <h2 class="text-sm text-gray-600 mb-3">Results for "{{ $q }}"</h2>

            @if($chats->isEmpty())
            <div class="bg-white p-6 rounded shadow-sm">No chats found.</div>
            @else
            <div class="space-y-3">
                @foreach($chats as $chat)
                <a href="{{ route('chats.show', $chat->uuid) }}" class="block bg-white p-4 rounded shadow-sm hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="font-medium">{{ $chat->title }}</div>
                        <div class="text-xs text-gray-400">{{ $chat->created_at->diffForHumans() }}</div>
                    </div>
                    @if($chat->messages->isNotEmpty())
                    <p class="text-sm text-gray-600 mt-2 truncate">{{ $chat->messages->pluck('content')->filter()->implode(' — ') }}</p>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
            @else
            <div class="bg-white p-6 rounded shadow-sm">Enter a query to search your chats and messages.</div>
            @endif
        </div>
    </div>

</x-layouts.app>