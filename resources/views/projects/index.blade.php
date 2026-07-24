<x-layouts.app :title="'My Projects'">
    <div class="min-h-[80vh] p-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Projects</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Manage and organize your AI projects</p>
            </div>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Project
            </a>
        </div>

        <!-- Projects Grid -->
        @if($projects->isEmpty())
        <div class="flex flex-col items-center justify-center py-20">
            <div class="w-24 h-24 mb-6 rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No projects yet</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Create your first project to get started</p>
            <a href="{{ route('projects.create') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition-all duration-300">
                Create Your First Project
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
            <a href="{{ route('projects.show', $project) }}" class="block p-6 bg-white dark:bg-zinc-900 rounded-2xl border border-gray-100 dark:border-zinc-800 hover:shadow-xl hover:shadow-purple-500/20 transition-all duration-300 group">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">{{ $project->chats->count() }} chats</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                    {{ $project->title }}
                </h3>
                @if($project->description)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">
                    {{ $project->description }}
                </p>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-zinc-800">
                    <span class="text-xs text-gray-400">Created {{ $project->created_at->diffForHumans() }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</x-layouts.app>