<x-layouts.app :title="__('Bank Transfer Payment')">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12">
        <div class="max-w-2xl mx-auto px-4">

            <!-- Back Link -->
            <a href="{{ route('subscription.pricing') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Plans
            </a>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6">
                    <h1 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Bank Transfer Payment
                    </h1>
                    <p class="text-indigo-100 mt-1">Complete your payment securely via bank transfer</p>
                </div>

                <div class="p-8">
                    <!-- Plan Summary -->
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 mb-8">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Selected Plan</p>
                                <h2 class="text-2xl font-bold text-gray-900 mt-1">{{ $planData['name'] }}</h2>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 uppercase tracking-wide">Amount to Pay</p>
                                <p class="text-3xl font-bold text-indigo-600">${{ $planData['price'] }}</p>
                                <p class="text-sm text-gray-500">{{ $planData['period'] }}ly</p>
                            </div>
                        </div>
                    </div>

                    @if(isset($currentSubscription) && $currentSubscription)
                    <!-- Current Subscription Notice -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-medium text-amber-800">You have an active subscription</p>
                                <p class="text-sm text-amber-600">Your new plan will start after your current subscription expires.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Bank Account Details -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Bank Account Details
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-blue-200">
                                <span class="text-blue-700 font-medium">Account Number:</span>
                                <span class="text-blue-900 font-bold text-lg tracking-wider">{{ $bankAccount }}</span>
                            </div>
                            <div class="py-2 text-sm text-blue-700">
                                <p> Please transfer exactly <strong class="text-xl">${{ $planData['price'] }}</strong> to the account above.</p>
                            </div>
                        </div>

                        <!-- Copy Button -->
                        <button onclick="copyAccountNumber()" class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            Copy Account Number
                        </button>
                    </div>

                    <!-- Payment Confirmation Form -->
                    <form action="{{ route('subscription.process-payment') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="plan" value="{{ $planId }}">

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Transaction Code / Reference
                                </span>
                            </label>
                            <input type="text"
                                name="transaction_code"
                                required
                                placeholder="Enter your bank transaction reference number"
                                class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                            <p class="text-xs text-gray-500 mt-1">Check your bank app or SMS for the transaction reference</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Confirm Amount Paid
                                </span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-semibold">$</span>
                                <input type="number"
                                    name="confirm_amount"
                                    required
                                    step="0.01"
                                    min="{{ $planData['price'] }}"
                                    value="{{ $planData['price'] }}"
                                    class="w-full pl-8 pr-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none font-semibold text-lg">
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="mt-1 w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                <span class="text-sm text-green-800">
                                    I confirm that I have made the bank transfer of <strong>${{ $planData['price'] }}</strong> to account number <strong>{{ $bankAccount }}</strong>
                                </span>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Confirm Payment
                        </button>
                    </form>

                    <!-- Help Text -->
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p> After making the transfer, enter your transaction details above.</p>
                        <p class="mt-1">Your subscription will be activated immediately after verification.</p>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 flex items-center justify-center gap-2 text-gray-500 text-sm">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Your payment is secure and encrypted</span>
            </div>
        </div>
    </div>

    <script>
        function copyAccountNumber() {
            navigator.clipboard.writeText('{{ $bankAccount }}').then(function() {
                alert('Account number copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>

</x-layouts.app>
