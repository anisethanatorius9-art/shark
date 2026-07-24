<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTableComponent extends Component
{
    use WithPagination;

    public $sortBy = 'date';
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

    #[\Livewire\Attributes\Computed]
    public function orders()
    {
        return Order::where('user_id', Auth::id())
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(5);
    }

    public function render()
    {
        return view('livewire.orders.table', [
            'orders' => $this->orders,
        ])->layout('components.layouts.app');
    }
}
