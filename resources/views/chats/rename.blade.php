<x-layouts.app :title="'Rename chat'">

    <div class="min-h-screen flex flex-col bg-gray-50">
        <div class="max-w-3xl mx-auto w-full px-4 py-10">
            <h1 class="text-2xl font-semibold mb-4">Rename Chat</h1>

            <div class="bg-white p-6 rounded shadow-sm">
                <p class="text-sm text-gray-600 mb-4">Change the title of this chat.</p>

                <div class="space-y-4">
                    <label class="block">
                        <span class="text-sm font-medium text-gray-700">Title</span>
                        <input type="text" value="{{ $chat->title }}" class="mt-1 block w-full rounded-md border-gray-200 focus:border-black focus:ring-black" />
                    </label>

                    <div class="flex items-center gap-3">
                        <button type="button" disabled class="px-4 py-2 bg-black text-white rounded-md">Save</button>
                        <a href="{{ route('chats.show', $chat->uuid) }}" class="px-4 py-2 border rounded-md text-sm">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>