<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    // Bank account details for direct transfer
    private const BANK_ACCOUNT = '10123416282';

    public function pricing()
    {
        $plans = [
            [
                'id' => 'go',
                'name' => 'Go',
                'price' => 6,
                'period' => 'day',
                'description' => 'Perfect for getting started',
                'features' => [
                    ' Explore topics in depth',
                    ' Chat longer and upload files',
                    ' Access to all features',
                    ' Basic AI assistance',
                ],
                'color' => 'green',
            ],
            [
                'id' => 'plus',
                'name' => 'Plus',
                'price' => 20,
                'period' => 'month',
                'description' => 'Best for regular users',
                'features' => [
                    ' Solve complex problems',
                    ' Long chat over multiple sessions',
                    ' Create and share custom instructions',
                    ' GPT-4.0 access & future features',
                    ' Priority support',
                ],
                'color' => 'indigo',
            ],
            [
                'id' => 'pro',
                'name' => 'Pro',
                'price' => 240,
                'period' => 'year',
                'description' => 'For power users',
                'features' => [
                    ' Master advanced tasks',
                    ' Full context with maximum memory',
                    ' Blue tick (verified profile)',
                    ' GPT-4.0 & all future features',
                    ' Priority 24/7 support',
                    ' Exclusive pro features',
                ],
                'color' => 'purple',
            ],
        ];

        return view('subscription.pricing', compact('plans'));
    }

    public function checkout($planId)
    {
        $plans = [
            'go' => ['name' => 'Go', 'price' => 6, 'period' => 'day', 'days' => 1],
            'plus' => ['name' => 'Plus', 'price' => 20, 'period' => 'month', 'days' => 30],
            'pro' => ['name' => 'Pro', 'price' => 240, 'period' => 'year', 'days' => 365],
        ];

        if (!isset($plans[$planId])) {
            return redirect()->route('subscription.pricing')->with('error', 'Invalid plan selected');
        }

        $planData = $plans[$planId];
        $bankAccount = self::BANK_ACCOUNT;

        // Check if user already has active subscription
        $user = Auth::user();
        $currentSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->first();

        return view('subscription.checkout', compact('planId', 'planData', 'bankAccount', 'currentSubscription'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:go,plus,pro',
            'transaction_code' => 'required|min:6',
            'confirm_amount' => 'required|numeric',
        ]);

        $plans = [
            'go' => ['name' => 'Go', 'price' => 6, 'days' => 1],
            'plus' => ['name' => 'Plus', 'price' => 20, 'days' => 30],
            'pro' => ['name' => 'Pro', 'price' => 240, 'days' => 365],
        ];

        $planId = $request->input('plan');
        $planData = $plans[$planId];
        $enteredAmount = $request->input('confirm_amount');

        // Verify amount matches
        if ($enteredAmount != $planData['price']) {
            return back()->with('error', 'Incorrect amount. Please verify you entered the exact amount.');
        }

        // For demo purposes, we'll accept any transaction code
        // In production, you would verify this with your bank API
        $user = Auth::user();

        // Create or update subscription
        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => $planId,
                'stripe_subscription_id' => 'BANK_TRANSFER_' . strtoupper($request->input('transaction_code')),
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addDays($planData['days']),
            ]
        );

        return redirect()->route('subscription.success', ['plan' => $planId])->with('success', 'Payment successful! Your subscription is now active.');
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if ($sessionId) {
            // Handle Stripe Checkout completion
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));
                $session = $stripe->checkout->sessions->retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $metadata = $session->metadata;
                    $user = User::find($metadata['user_id']);

                    if ($user) {
                        Auth::login($user); // Ensure user is logged in

                        $expiresAt = now()->addDays($metadata['days']);

                        if ($session->mode === 'subscription') {
                            // For recurring subscriptions
                            $subscription = $stripe->subscriptions->retrieve($session->subscription);
                            Subscription::updateOrCreate(
                                ['user_id' => $user->id],
                                [
                                    'plan' => $metadata['plan_id'],
                                    'stripe_subscription_id' => $subscription->id,
                                    'status' => 'active',
                                    'current_period_start' => now()->createFromTimestamp($subscription->current_period_start),
                                    'current_period_end' => now()->createFromTimestamp($subscription->current_period_end),
                                ]
                            );
                        } else {
                            // One-time payment
                            Subscription::updateOrCreate(
                                ['user_id' => $user->id],
                                [
                                    'plan' => $metadata['plan_id'],
                                    'stripe_subscription_id' => $session->id,
                                    'status' => 'active',
                                    'current_period_start' => now(),
                                    'current_period_end' => $expiresAt,
                                ]
                            );
                        }

                        // Update user subscription
                        $user->update(['subscription_id' => $user->subscription->id ?? null]);

                        // Send email notification
                        Mail::raw("Dear {$user->name},\n\nThank you for subscribing to Shark AI {$metadata['plan_name']} plan!\n\nYour payment has been processed successfully.\nSubscription details:\n- Plan: {$metadata['plan_name']}\n- Amount: $" . ($session->amount_total / 100) . "\n- Status: Active\n\nYou now have access to all premium features.\n\nBest regards,\nShark AI Team", function ($message) use ($user) {
                            $message->to($user->email)
                                    ->subject('Shark AI Subscription Activated');
                        });

                        $plan = $metadata['plan_id'];
                        $plans = [
                            'go' => ['name' => 'Go', 'price' => 6, 'period' => 'day'],
                            'plus' => ['name' => 'Plus', 'price' => 20, 'period' => 'month'],
                            'pro' => ['name' => 'Pro', 'price' => 240, 'period' => 'year'],
                        ];
                        $planData = $plans[$plan] ?? $plans['plus'];

                        return view('subscription.success', compact('plan', 'planData'))->with('success', 'Payment successful! Check your email for confirmation.');
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Stripe session verification failed: ' . $e->getMessage());
                return redirect()->route('subscription.pricing')->with('error', 'Payment verification failed. Please contact support.');
            }
        }

        // Fallback for bank transfer
        $plan = $request->query('plan', 'plus');
        $plans = [
            'go' => ['name' => 'Go', 'price' => 6, 'period' => 'day'],
            'plus' => ['name' => 'Plus', 'price' => 20, 'period' => 'month'],
            'pro' => ['name' => 'Pro', 'price' => 240, 'period' => 'year'],
        ];
        $planData = $plans[$plan] ?? $plans['plus'];

        return view('subscription.success', compact('plan', 'planData'));
    }

    public function cancel()
    {
        return view('subscription.cancel');
    }
}
