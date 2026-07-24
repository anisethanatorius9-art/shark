<x-layouts.app :title="__('Library')">

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-2">Library</h1>
            <p class="text-gray-600 mb-6">A collection of your saved resources and documents.</p>

            <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z" />
                </svg>
                <p class="text-gray-600">Your library is empty.</p>
                <p class="text-sm text-gray-500 mt-1">Saved resources will appear here.</p>
            </div>
        </div>
    </div>

</x-layouts.app>
