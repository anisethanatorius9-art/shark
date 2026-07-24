<?php

namespace App\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectCreateComponent extends Component
{
    public $title = '';
    public $description = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'title.required' => 'Project title is required.',
        'title.max' => 'Project title cannot exceed 255 characters.',
        'description.max' => 'Description cannot exceed 1000 characters.',
    ];

    public function createProject()
    {
        $this->validate();

        $project = Project::create([
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully!');
    }

    public function render()
    {
        return view('livewire.projects.create')
            ->layout('components.layouts.app');
    }
}
