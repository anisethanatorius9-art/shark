<x-layouts.app :title="$chat->title">

    <div class="min-h-screen flex flex-col bg-gray-50">

        <div class="max-w-3xl mx-auto w-full px-4 py-10">
            <h1 class="text-2xl font-semibold mb-4">Group: {{ $chat->title }}</h1>
            <p class="text-sm text-gray-600 mb-6">Manage group members and settings for this chat.</p>

            <!-- MEMBERS LIST -->
            <div class="bg-white rounded shadow-sm p-6 mb-6">
                <h3 class="font-semibold mb-4">Members</h3>
                @if(isset($chat->members) && $chat->members->isNotEmpty())
                <ul class="space-y-3">
                    @foreach($chat->members as $member)
                    <li class="flex items-center justify-between p-3 border rounded-md hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            @if($member->avatar)
                            <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            @endif
                            <div>
                                <p class="font-medium">{{ $member->name }}</p>
                                <p class="text-xs text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                        @if($member->id !== auth()->id())
                        <button class="px-3 py-1 text-sm border rounded-md hover:bg-red-50 text-red-600">Remove</button>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="p-6 text-center text-gray-600">
                    <p>No members in this group yet.</p>
                </div>
                @endif
            </div>

            <!-- TYPING INDICATOR SECTION -->
            <div class="bg-white rounded shadow-sm p-6 mb-6">
                <h3 class="font-semibold mb-4">Live Activity</h3>
                <div x-data="{ typingUsers: [] }" class="space-y-2">
                    <div x-show="typingUsers.length > 0" class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></span>
                        </div>
                        <span x-text="`${typingUsers.join(', ')} ${typingUsers.length === 1 ? 'is' : 'are'} typing...`"></span>
                    </div>
                    <div x-show="typingUsers.length === 0" class="text-sm text-gray-400">No one is typing</div>
                </div>
            </div>

            <!-- ADD FRIENDS SECTION -->
            <div class="bg-white rounded shadow-sm p-6">
                <h3 class="font-semibold mb-4">Add Friends</h3>
                <form method="POST" class="space-y-3">
                    @csrf
                    <select name="friends" class="w-full px-4 py-2 rounded-md border border-gray-200 focus:ring-black focus:border-black">
                        <option value="">Select a friend to add...</option>
                        @foreach(auth()->user()->friends ?? [] as $friend)
                        @unless($chat->members?->contains($friend->id))
                        <option value="{{ $friend->id }}">{{ $friend->name }}</option>
                        @endunless
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800">Add to Group</button>
                </form>
            </div>
        </div>

    </div>

</x-layouts.app>
