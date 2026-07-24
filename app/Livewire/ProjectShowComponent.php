<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectShowComponent extends Component
{
    public Project $project;
    public $isEditing = false;
    public $editName = '';
    public $editDescription = '';

    public function mount(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403);
        }

        $this->project = $project;
        $this->editName = $project->title;
        $this->editDescription = $project->description;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function updateProject()
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:1000',
        ]);

        $this->project->update([
            'title' => $this->editName,
            'description' => $this->editDescription,
        ]);

        $this->isEditing = false;
        $this->dispatch('project-updated', 'Project updated successfully!');
    }

    public function deleteProject()
    {
        $this->project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }

    public function render()
    {
        return view('livewire.projects.show')
            ->layout('components.layouts.app');
    }
}
