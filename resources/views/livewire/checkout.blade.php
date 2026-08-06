<div class="min-h-screen p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-zinc-900 dark:to-zinc-800">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('subscription.pricing') }}" class="inline-flex items-center gap-2 text-purple-600 dark:text-purple-400 hover:underline mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Pricing
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Complete Your Purchase</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Secure payment for {{ $plan['name'] }} plan</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white dark:bg-zinc-800 rounded-2xl p-6 mb-6 border border-gray-100 dark:border-zinc-700">
            <div class="flex items-center justify-between pb-6 border-b border-gray-100 dark:border-zinc-700">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $plan['name'] }} Plan</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Billed {{ $plan['period'] }}ly</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ $plan['price'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">/ {{ $plan['period'] }}</p>
                </div>
            </div>

            @if($planId === 'go')
            <div class="mt-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Includes:</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Explore topics in depth</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Chat longer and upload files</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Access to all features</span>
                    </li>
                </ul>
            </div>
            @elseif($planId === 'plus')
            <div class="mt-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Includes:</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Solve complex problems</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>GPT-4.0 access & future features</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Priority support</span>
                    </li>
                </ul>
            </div>
            @else
            <div class="mt-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Includes:</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Master advanced tasks</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Blue tick (verified profile)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                        <span>Priority 24/7 support</span>
                    </li>
                </ul>
            </div>
            @endif
        </div>

        <!-- Payment Form -->
        <form wire:submit="processPayment" class="bg-white dark:bg-zinc-800 rounded-2xl p-6 border border-gray-100 dark:border-zinc-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Billing Information</h2>

            <!-- Full Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                <input
                    wire:model="fullName"
                    type="text"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                @error('fullName') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input
                    wire:model="email"
                    type="email"
                    class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-700 text-gray-900 dark:text-white">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Payment Method</label>
                <div class="space-y-2">
                    <label class="flex items-center p-3 border border-gray-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700">
                        <input type="radio" wire:model="paymentMethod" value="card" class="w-4 h-4">
                        <span class="ml-3 text-gray-900 dark:text-white">Credit/Debit Card</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 dark:border-zinc-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-zinc-700">
                        <input type="radio" wire:model="paymentMethod" value="wallet" class="w-4 h-4">
                        <span class="ml-3 text-gray-900 dark:text-white">Wallet / Apple Pay / Google Pay</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Your browser will show available wallet payments when supported. After clicking Complete Purchase, Stripe Checkout will redirect you to the payment screen.</p>
                @if($paymentMethod === 'wallet')
                <p class="text-xs text-blue-600 dark:text-blue-300 mt-2">If your device supports it, Google Pay or Apple Pay will appear in the Stripe checkout window.</p>
                @endif

                <button type="submit"
                    wire:loading.attr="disabled"
                    class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove>Complete Purchase - ${{ $plan['price'] }}</span>
                    <span wire:loading>Processing...</span>
                </button>

                <!-- Secure Payment Notice -->
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">
                    🔒 Your payment is secure and encrypted
                </p>
        </form>
    </div>
</div>