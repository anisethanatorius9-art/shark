<x-layouts.app :title="__('Payment Successful')">

    <div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 py-12">
        <div class="max-w-2xl mx-auto px-4">

            <!-- Success Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Success Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-8 text-center">
                    <!-- Animated Check Mark -->
                    <div class="relative inline-flex">
                        <div class="w-24 h-24 mx-auto bg-white rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-16 h-16 text-green-500 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-lg animate-pulse">

                        </div>
                    </div>

                    <h1 class="text-3xl font-bold text-white mt-6">Payment Successful!</h1>
                    <p class="text-green-100 mt-2 text-lg">Your subscription is now active</p>
                </div>

                <div class="p-8">
                    <!-- Plan Details -->
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 mb-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Your Plan</p>
                                <h2 class="text-2xl font-bold text-gray-900 mt-1 flex items-center gap-2">
                                    @if($plan === 'go')
                                    Go
                                    @elseif($plan === 'plus')
                                    Plus
                                    @else
                                    Pro
                                    @endif
                                </h2>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Amount Paid</p>
                                <p class="text-2xl font-bold text-green-600">${{ $planData['price'] }}</p>
                                <p class="text-sm text-gray-500">{{ $planData['period'] }}ly</p>
                            </div>
                        </div>
                        @if(auth()->check() && auth()->user()->hasVerifiedBadge())
                        <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-emerald-100 dark:bg-emerald-900/30 px-4 py-2 text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586 7.707 11.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Google Play verified badge active
                        </div>
                        @endif
                    </div>

                    <!-- What's Included -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            Your Plan Features
                        </h3>

                        <div class="grid grid-cols-1 gap-3">
                            @if($plan === 'go')
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                <span class="text-green-500 text-xl"></span>
                                <span class="text-gray-700">Explore topics in depth</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                <span class="text-green-500 text-xl"></span>
                                <span class="text-gray-700">Chat longer and upload files</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                <span class="text-green-500 text-xl"></span>
                                <span class="text-gray-700">Access to all features</span>
                            </div>
                            @elseif($plan === 'plus')
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                                <span class="text-indigo-500 text-xl"></span>
                                <span class="text-gray-700">Solve complex problems</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                                <span class="text-indigo-500 text-xl"></span>
                                <span class="text-gray-700">Long chat over multiple sessions</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                                <span class="text-indigo-500 text-xl"></span>
                                <span class="text-gray-700">Create and share custom instructions</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-indigo-50 rounded-lg">
                                <span class="text-indigo-500 text-xl"></span>
                                <span class="text-gray-700">GPT-4.0 access & future features</span>
                            </div>
                            @else
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                <span class="text-purple-500 text-xl"></span>
                                <span class="text-gray-700">Master advanced tasks and topics</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                <span class="text-purple-500 text-xl"></span>
                                <span class="text-gray-700">Full context with maximum memory</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                <span class="text-purple-500 text-xl"></span>
                                <span class="text-gray-700">Blue tick (verified profile)</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                <span class="text-purple-500 text-xl"></span>
                                <span class="text-gray-700">GPT-4.0 & all future features</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('subscription.google-play.verify.form') }}" class="flex-1 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Verify Google Play Purchase
                        </a>

                        <a href="{{ route('dashboard') }}"
                            class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Go to Dashboard
                        </a>

                        <a href="{{ route('chats.create') }}"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-900 font-bold py-4 px-6 rounded-xl transition-all flex items-center justify-center gap-2 text-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Start Chatting
                        </a>
                    </div>

                    <!-- Support Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800 text-center">
                            <strong>Need help?</strong> Contact our support team if you have any questions about your subscription.
                        </p>
                    </div>
                </div>
            </div>

            @if(!empty($statusNote))
            <div class="mt-6 rounded-2xl bg-yellow-50 border border-yellow-200 p-4 text-sm text-yellow-800">
                {{ $statusNote }}
            </div>
            @endif

            <!-- Transaction Details -->
            <div class="mt-6 text-center text-sm text-gray-500">
                <p>Transaction ID: {{ strtoupper(uniqid('TXN-')) }}</p>
                <p class="mt-1">Thank you for your purchase! </p>
            </div>
        </div>
    </div>

</x-layouts.app>