<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $search = '';
    public $searchResults = [];
    public $showSearch = false;

    public function toggleSearch()
    {
        $this->showSearch = !$this->showSearch;
        if (!$this->showSearch) {
            $this->search = '';
            $this->searchResults = [];
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            // Search chats or something
            // For now, dummy
            $this->searchResults = ['Chat 1', 'Chat 2'];
        } else {
            $this->searchResults = [];
        }
    }

    public function selectChat($chatId)
    {
        // Navigate to chat
        return redirect()->route('chats.show', $chatId);
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}