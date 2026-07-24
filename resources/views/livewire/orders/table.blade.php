<div class="min-h-[80vh] p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Orders</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage recent customer orders with sorting and pagination.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500 dark:text-gray-400">Sort by:</span>
            <div class="inline-flex rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden bg-white dark:bg-zinc-900">
                <button wire:click="sort('date')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'date' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                    Date
                </button>
                <button wire:click="sort('status')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'status' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                    Status
                </button>
                <button wire:click="sort('amount')" class="px-4 py-2 text-sm font-medium {{ $sortBy === 'amount' ? 'text-white bg-indigo-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                    Amount
                </button>
            </div>
        </div>
    </div>

    <flux:table :paginate="$orders">
        <flux:table.columns>
            <flux:table.column>Customer</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'date'" :direction="$sortDirection" wire:click="sort('date')">Date</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'status'" :direction="$sortDirection" wire:click="sort('status')">Status</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'amount'" :direction="$sortDirection" wire:click="sort('amount')">Amount</flux:table.column>
            <flux:table.column>Actions</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($orders as $order)
            <flux:table.row :key="$order->id">
                <flux:table.cell class="flex items-center gap-3">
                    <flux:avatar size="xs" src="{{ $order->customer_avatar }}" />
                    {{ $order->customer }}
                </flux:table.cell>

                <flux:table.cell class="whitespace-nowrap">{{ $order->date->format('M j, Y') }}</flux:table.cell>

                <flux:table.cell>
                    <flux:badge size="sm" :color="$order->status_color" inset="top bottom">{{ $order->status }}</flux:badge>
                </flux:table.cell>

                <flux:table.cell variant="strong">{{ $order->amount }}</flux:table.cell>

                <flux:table.cell>
                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                </flux:table.cell>
            </flux:table.row>
            @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-center py-10 text-sm text-gray-500 dark:text-gray-400">
                    No orders found. Create an order or add data to the orders table.
                </flux:table.cell>
            </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</div>