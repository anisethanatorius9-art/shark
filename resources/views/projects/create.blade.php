<x-layouts.app :title="'Create New Project'">
    <div class="min-h-[80vh] flex items-center justify-center p-6">
        <div class="w-full max-w-lg">
            <!-- Header Section -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 mb-6 rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 shadow-lg shadow-purple-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900 dark:from-white dark:via-gray-200 dark:to-white bg-clip-text text-transparent mb-3">
                    Create New Project
                </h1>
                <p class="text-gray-500 dark:text-gray-400">
                    Give your project a name and description to get started
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-xl shadow-gray-200/50 dark:shadow-zinc-800/50 border border-gray-100 dark:border-zinc-800 p-8">
                <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Project Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Project Title
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            required
                            placeholder="Enter project title..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:shadow-lg focus:shadow-purple-500/20 transition-all duration-300">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Description
                            <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Describe your project..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent focus:shadow-lg focus:shadow-purple-500/20 transition-all duration-300 resize-none"></textarea>
                    </div>

                    <!-- Amazing Create Button -->
                    <div class="pt-4">
                        <button
                            type="submit"
                            class="group relative w-full overflow-hidden rounded-xl px-6 py-4 font-semibold text-white transition-all duration-300 hover:shadow-2xl hover:shadow-purple-500/40">
                            <!-- Gradient Background -->
                            <span class="absolute inset-0 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600"></span>
                            <span class="absolute inset-0 bg-gradient-to-r from-blue-700 via-purple-700 to-pink-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>

                            <!-- Shimmer Effect -->
                            <span class="absolute inset-0 overflow-hidden rounded-xl">
                                <span class="absolute -left-full top-0 h-full w-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>
                            </span>

                            <!-- Button Content -->
                            <span class="relative flex items-center justify-center gap-3">
                                <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-lg">Create Project</span>
                            </span>
                        </button>
                    </div>

                    <!-- Cancel Link -->
                    <div class="text-center pt-2">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cancel and go back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>