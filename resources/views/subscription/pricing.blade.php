<x-layouts.app :title="__('Subscription Plans')">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full mb-4">
                    Premium Plans
                </span>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Choose Your Perfect Plan
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Unlock the full potential of AI with our flexible subscription plans.
                    Start with a plan that fits your needs.
                </p>
            </div>

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($plans as $index => $plan)
                <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl {{ $index === 1 ? 'ring-2 ring-indigo-500 scale-105 md:scale-110' : '' }}">
                    <!-- Popular Badge -->
                    @if($index === 1)
                    <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-2 text-sm font-semibold">
                        Most Popular
                    </div>
                    @endif

                    <div class="p-8 {{ $index === 1 ? 'pt-12' : '' }}">
                        <!-- Plan Icon -->
                        <div class="w-16 h-16 mx-auto mb-6 rounded-2xl flex items-center justify-center
                            {{ $index === 0 ? 'bg-green-100' : ($index === 1 ? 'bg-indigo-100' : 'bg-purple-100') }}">
                            @if($index === 0)
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @elseif($index === 1)
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @else
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            @endif
                        </div>

                        <!-- Plan Name -->
                        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">{{ $plan['name'] }}</h2>

                        <!-- Price -->
                        <div class="text-center mb-6">
                            <span class="text-5xl font-bold text-gray-900">${{ $plan['price'] }}</span>
                            <span class="text-gray-500 ml-2">/ {{ $plan['period'] }}</span>
                        </div>

                        <!-- Features -->
                        <ul class="space-y-4 mb-8">
                            @foreach($plan['features'] as $feature)
                            <li class="flex items-start gap-3 text-gray-600">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Subscribe Button -->
                        <a href="{{ route('subscription.checkout', $plan['id']) }}"
                            class="block w-full py-4 px-6 text-center font-bold rounded-xl transition-all duration-300 transform hover:scale-105
                           {{ $index === 0 ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white shadow-lg hover:shadow-xl' : ($index === 1 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white shadow-lg hover:shadow-xl' : 'bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white shadow-lg hover:shadow-xl') }}">
                            Get Started Now →
                        </a>
                    </div>

                    <!-- Bottom accent -->
                    <div class="h-2 bg-gradient-to-r
                        {{ $index === 0 ? 'from-green-500 to-emerald-600' : ($index === 1 ? 'from-indigo-500 to-purple-600' : 'from-purple-500 to-pink-600') }}">
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <p class="text-gray-700 dark:text-gray-300 mb-4">Purchased through Google Play?</p>
                <a href="{{ route('subscription.google-play.verify.form') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-full shadow-lg hover:from-cyan-700 hover:to-blue-700 transition">
                    <span>Verify Google Play Purchase</span>
                </a>
            </div>

            @if(auth()->check() && auth()->user()->hasVerifiedBadge())
            <div class="mt-8 rounded-3xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 p-5 text-center">
                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">Your account is already verified with Google Play.</p>
                <p class="text-sm text-emerald-700 dark:text-emerald-300 mt-1">Enjoy full subscription access and verified badge benefits.</p>
            </div>
            @endif

            <!-- Trust Badges -->
            <div class="mt-16 text-center">
                <p class="text-gray-500 mb-6">Trusted by thousands of users worldwide</p>
                <div class="flex justify-center items-center gap-8 text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">Secure Payment</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm">4.9/5 Rating</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm">24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>