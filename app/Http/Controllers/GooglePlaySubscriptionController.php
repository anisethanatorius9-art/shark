<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\GooglePlayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GooglePlaySubscriptionController extends Controller
{
    public function showVerifyForm()
    {
        $plans = [
            'go' => ['name' => 'Go', 'price' => 6, 'period' => 'day'],
            'plus' => ['name' => 'Plus', 'price' => 20, 'period' => 'month'],
            'pro' => ['name' => 'Pro', 'price' => 240, 'period' => 'year'],
        ];

        return view('subscription.google-play-verify', compact('plans'));
    }

    public function verify(Request $request, GooglePlayService $googlePlayService)
    {
        $request->validate([
            'plan' => 'required|in:go,plus,pro',
            'purchase_token' => 'required|string|max:1024',
            'package_name' => 'nullable|string|max:255',
        ]);

        $packageName = $request->input('package_name') ?: config('services.google_play.package_name');
        $plan = $request->input('plan');
        $purchaseToken = $request->input('purchase_token');
        $productId = $googlePlayService->getProductIdForPlan($plan);

        try {
            $response = $googlePlayService->verifySubscriptionPurchase($packageName, $productId, $purchaseToken);

            $expiryTime = null;
            if (isset($response['expiryTimeMillis'])) {
                $expiryTime = now()->createFromTimestampMs($response['expiryTimeMillis']);
            }

            $subscription = Subscription::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'plan' => $plan,
                    'google_purchase_token' => $purchaseToken,
                    'google_subscription_id' => $response['subscriptionId'] ?? null,
                    'google_order_id' => $response['orderId'] ?? null,
                    'stripe_subscription_id' => $response['orderId'] ?? $purchaseToken,
                    'status' => $response['paymentState'] === 1 ? 'active' : 'pending',
                    'current_period_start' => now(),
                    'current_period_end' => $expiryTime ?: now()->addDays($this->planToDays($plan)),
                ]
            );

            $user = Auth::user();
            $user->is_verified = true;
            $user->save();

            return redirect()->route('subscription.google-play.verify.form')->with('success', 'Google Play purchase verified successfully. Your account is now verified and your subscription is active.');
        } catch (\RuntimeException $e) {
            Log::error('Google Play configuration error: ' . $e->getMessage(), [
                'plan' => $plan,
                'purchase_token' => $purchaseToken,
            ]);

            return back()->withInput()->with('error', 'Google Play service account credentials are not configured. Please set GOOGLE_PLAY_SERVICE_ACCOUNT_JSON or GOOGLE_PLAY_SERVICE_ACCOUNT_PATH.');
        } catch (\Throwable $e) {
            Log::error('Google Play verification error: ' . $e->getMessage(), [
                'plan' => $plan,
                'purchase_token' => $purchaseToken,
            ]);

            return back()->withInput()->with('error', 'Unable to verify the Google Play purchase. Please check your purchase token and try again.');
        }
    }

    protected function planToDays(string $plan): int
    {
        return match ($plan) {
            'go' => 1,
            'plus' => 30,
            'pro' => 365,
            default => 30,
        };
    }
}
