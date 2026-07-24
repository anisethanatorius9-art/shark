<x-layouts.app :title="__('Apps')">

    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-2xl font-semibold mb-6">Available Apps</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($apps as $app)
                <div id="{{ $app['id'] }}" class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-black text-white flex items-center justify-center text-2xl">{{ $app['icon'] ?? '📱' }}</div>
                        <div>
                            <h2 class="text-lg font-semibold">{{ $app['name'] }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $app['description'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-3">
                        <a href="{{ $app['url'] }}" target="_blank" class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800">Open</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</x-layouts.app>