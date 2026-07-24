<?php

namespace App\Livewire;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Stripe\StripeClient;

class CheckoutComponent extends Component
{
    public $plan;
    public $planId;
    public $fullName = '';
    public $email = '';
    public $paymentMethod = 'card';
    public $isProcessing = false;

    protected $rules = [
        'fullName' => 'required|string|max:255',
        'email' => 'required|email',
        'paymentMethod' => 'required|in:card,bank',
    ];

    public function mount($plan)
    {
        $this->planId = $plan;

        // Plan details
        $plans = [
            'go' => ['name' => 'Go', 'price' => 6, 'period' => 'day', 'days' => 1],
            'plus' => ['name' => 'Plus', 'price' => 20, 'period' => 'month', 'days' => 30],
            'pro' => ['name' => 'Pro', 'price' => 240, 'period' => 'year', 'days' => 365],
        ];

        if (!isset($plans[$plan])) {
            abort(404);
        }

        $this->plan = $plans[$plan];

        // Pre-fill user info
        $user = Auth::user();
        $this->fullName = $user->name;
        $this->email = $user->email;
    }

    public function processPayment()
    {
        $this->validate();

        $this->isProcessing = true;

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $priceData = [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $this->plan['name'] . ' Plan',
                ],
                'unit_amount' => $this->plan['price'] * 100, // cents
            ];

            if ($this->plan['period'] === 'day') {
                // For daily, use one-time payment
                $session = $stripe->checkout->sessions->create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => $priceData,
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('subscription.cancel'),
                    'customer_email' => $this->email,
                    'metadata' => [
                        'user_id' => Auth::id(),
                        'plan_id' => $this->planId,
                        'plan_name' => $this->plan['name'],
                        'days' => $this->plan['days'],
                    ],
                ]);
            } else {
                // For monthly/yearly, create subscription
                $price = $stripe->prices->create($priceData + [
                    'recurring' => [
                        'interval' => $this->plan['period'] === 'month' ? 'month' : 'year',
                    ],
                ]);

                $session = $stripe->checkout->sessions->create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price' => $price->id,
                        'quantity' => 1,
                    ]],
                    'mode' => 'subscription',
                    'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('subscription.cancel'),
                    'customer_email' => $this->email,
                    'metadata' => [
                        'user_id' => Auth::id(),
                        'plan_id' => $this->planId,
                        'plan_name' => $this->plan['name'],
                        'days' => $this->plan['days'],
                    ],
                ]);
            }

            $this->isProcessing = false;
            return redirect($session->url);
        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->dispatch('payment-error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout')
            ->layout('components.layouts.app');
    }
}
