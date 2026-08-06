<x-layouts.app :title="__('Google Play Verification')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-cyan-50 py-12">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
                <div class="p-8 bg-gradient-to-r from-cyan-600 to-blue-600 text-white">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-bold">Verify Google Play Subscription</h1>
                                <p class="mt-3 text-sm text-cyan-100 max-w-2xl">Enter your purchase token to verify your Google Play subscription and activate your Shark AI account.</p>
                            </div>
                            @if(auth()->check() && auth()->user()->hasVerifiedBadge())
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white border border-white/20">
                                <svg class="w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586 7.707 11.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Verified Purchase
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    @if(session('success'))
                    <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-900">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-900">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="rounded-2xl bg-yellow-50 border border-yellow-200 p-4 text-yellow-900">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(auth()->check() && auth()->user()->hasVerifiedBadge())
                    <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 p-4 text-emerald-900 dark:text-emerald-100">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 12.586 7.707 11.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-semibold">Verified badge active</p>
                                <p class="text-sm text-emerald-800 dark:text-emerald-200">Your Google Play purchase has already been verified.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="rounded-3xl border border-gray-200 dark:border-zinc-700 p-6 bg-gray-50 dark:bg-zinc-950">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">What you need</h2>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            <li>1. Your Google Play purchase token.</li>
                            <li>2. The plan you purchased (Go, Plus, or Pro).</li>
                            <li>3. Your app package name, if the default package name is not configured.</li>
                        </ul>
                    </div>

                    <form action="{{ route('subscription.google-play.verify.post') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Purchased Plan</label>
                            <select name="plan" class="w-full rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 p-3">
                                @foreach($plans as $key => $plan)
                                <option value="{{ $key }}" @selected(old('plan')===$key)>{{ $plan['name'] }} - ${{ $plan['price'] }}/{{ $plan['period'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Google Play Purchase Token</label>
                            <input type="text" name="purchase_token" value="{{ old('purchase_token') }}" required class="w-full rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 p-3" placeholder="Enter your Google Play purchase token">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">Package Name (optional)</label>
                            <input type="text" name="package_name" value="{{ old('package_name') }}" class="w-full rounded-2xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 p-3" placeholder="com.example.sharkapp">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Leave empty to use the default package name configured on the server.</p>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-gradient-to-r from-cyan-600 to-blue-600 text-white py-3 text-sm font-semibold hover:from-cyan-700 hover:to-blue-700 transition">Verify Purchase</button>
                    </form>

                    <div class="rounded-3xl border border-gray-200 dark:border-zinc-700 p-6 bg-white dark:bg-zinc-950">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Need help?</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">If your subscription was purchased through Google Play, use the purchase token from your Android app receipt. Once verified, your Shark account will receive the verified badge and active subscription access.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>