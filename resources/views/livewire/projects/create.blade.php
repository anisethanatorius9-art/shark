<div class="min-h-[80vh] p-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:underline mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Projects
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Project</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Start organizing your AI work with a new project</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 border border-gray-100 dark:border-zinc-800 shadow-lg">
            <form wire:submit="createProject" class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Title <span class="text-red-500">*</span></label>
                    <input
                        wire:model="title"
                        type="text"
                        id="title"
                        placeholder="Enter your project title"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Input -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                    <textarea
                        wire:model="description"
                        id="description"
                        rows="4"
                        placeholder="Describe your project (optional)"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"></textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-6">
                    <button
                        type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-purple-500/30 transition-all duration-300">
                        Create Project
                    </button>
                    <a
                        href="{{ route('projects.index') }}"
                        class="px-6 py-3 border border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>