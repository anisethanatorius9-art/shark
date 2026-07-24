<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardComponent extends Component
{
    public $totalChats;
    public $totalMessages;
    public $totalProjects;
    public $userPlan;
    public $recentChats = [];
    public $recentProjects = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $user = Auth::user();

        $this->totalChats = $user->chats()->count();
        $this->totalMessages = $user->chats()
            ->with('messages')
            ->get()
            ->sum(fn($c) => $c->messages->count());
        $this->totalProjects = $user->projects()->count();
        $this->userPlan = $user->subscription?->name ?? 'Free';

        // Get recent chats (last 5)
        $this->recentChats = Chat::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->with('messages')
            ->get()
            ->map(fn($chat) => [
                'id' => $chat->id,
                'uuid' => $chat->uuid,
                'name' => $chat->name,
                'message_count' => $chat->messages->count(),
                'updated_at' => $chat->updated_at->diffForHumans(),
            ])
            ->toArray();

        // Get recent projects (last 5)
        $this->recentProjects = Project::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($project) => [
                'id' => $project->id,
                'name' => $project->name,
                'updated_at' => $project->updated_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app');
    }
}
