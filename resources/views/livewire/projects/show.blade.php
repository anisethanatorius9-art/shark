<div class="min-h-[80vh] p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:underline mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Projects
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $project->title }}</h1>
            </div>
            <button
                wire:click="toggleEdit"
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                {{ $isEditing ? 'Cancel' : 'Edit' }}
            </button>
        </div>

        @if($isEditing)
        <!-- Edit Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 border border-gray-100 dark:border-zinc-800 shadow-lg mb-8">
            <form wire:submit="updateProject" class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label for="editName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Title</label>
                    <input
                        wire:model="editName"
                        type="text"
                        id="editName"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('editName')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div>
                    <label for="editDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea
                        wire:model="editDescription"
                        id="editDescription"
                        rows="4"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"></textarea>
                    @error('editDescription')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        @else
        <!-- View Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Description</h2>
                    @if($project->description)
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $project->description }}</p>
                    @else
                    <p class="text-gray-500 dark:text-gray-500 italic">No description provided</p>
                    @endif
                </div>

                <!-- Associated Chats -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Associated Chats</h2>
                    @if($project->chats->count() > 0)
                    <div class="space-y-3">
                        @foreach($project->chats as $chat)
                        <a href="{{ route('chats.show', $chat->uuid) }}" class="block p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition border border-gray-100 dark:border-zinc-700">
                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $chat->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $chat->messages->count() }} messages</p>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 dark:text-gray-500 italic">No chats associated with this project yet</p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Project Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Created</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Last Updated</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->updated_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">Total Chats</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->chats->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800">
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Actions</h3>
                    <button
                        wire:click="deleteProject"
                        wire:confirm="Are you sure you want to delete this project?"
                        class="w-full px-4 py-2 bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-500/20 transition font-medium">
                        Delete Project
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>