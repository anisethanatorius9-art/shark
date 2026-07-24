<div class="min-h-[80vh] p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Projects</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage and organize your AI projects.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-purple-500/30 transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Project
        </a>
    </div>

    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="inline-flex rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-900">
            <button wire:click="sort('title')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'title' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                Project
            </button>
            <button wire:click="sort('created_at')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'created_at' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                Created
            </button>
            <button wire:click="sort('updated_at')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'updated_at' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                Updated
            </button>
            <button wire:click="sort('chats_count')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'chats_count' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                Chats
            </button>
        </div>
    </div>

    <flux:table :paginate="$projects">
        <flux:table.columns>
            <flux:table.column>Project</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection" wire:click="sort('created_at')">Created</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'updated_at'" :direction="$sortDirection" wire:click="sort('updated_at')">Updated</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'chats_count'" :direction="$sortDirection" wire:click="sort('chats_count')">Chats</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($projects as $project)
            <flux:table.row :key="$project->id">
                <flux:table.cell class="space-y-1">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $project->title }}</div>
                    @if($project->description)
                    <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $project->description }}</div>
                    @endif
                </flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $project->created_at->format('M j, Y') }}</flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $project->updated_at->diffForHumans() }}</flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm" inset="top bottom" color="blue">{{ $project->chats_count }} chats</flux:badge>
                </flux:table.cell>

                <flux:table.cell>
                    <div class="flex items-center gap-2">
                        <flux:button variant="ghost" size="sm" icon="eye" inset="top bottom" href="{{ route('projects.show', $project) }}" wire:navigate>
                            View
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="pencil" inset="top bottom" href="{{ route('projects.show', $project) }}" wire:navigate>
                            Edit
                        </flux:button>
                        <flux:button variant="ghost" size="sm" icon="trash" inset="top bottom" wire:click="deleteProject({{ $project->id }})" onclick="return confirm('Delete this project?')">
                            Delete
                        </flux:button>
                    </div>
                </flux:table.cell>
            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-center py-10 text-sm text-gray-500 dark:text-gray-400">
                    No projects yet. Click “New Project” to create one.
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>