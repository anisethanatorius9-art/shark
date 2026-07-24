<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectIndexComponent extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['sortBy', 'sortDirection'];
    protected $paginationTheme = 'tailwind';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function deleteProject(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $project->delete();
        $this->dispatch('project-deleted', 'Project deleted successfully!');
    }

    public function render()
    {
        $projects = Project::withCount('chats')
            ->where('user_id', Auth::id())
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        return view('livewire.projects.index', ['projects' => $projects])
            ->layout('components.layouts.app');
    }
}
