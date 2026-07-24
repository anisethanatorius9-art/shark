<x-layouts.app :title="__('Payment Cancelled')">

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-md mx-auto px-4 text-center">
            <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <h1 class="text-2xl font-semibold mb-2">Payment Cancelled</h1>
            <p class="text-gray-600 mb-6">Your payment was cancelled. No charges have been made.</p>

            <a href="{{ route('subscription.pricing') }}" class="inline-block px-4 py-2 bg-black text-white rounded-md hover:bg-gray-800">
                Back to Pricing
            </a>
        </div>
    </div>

</x-layouts.app>
