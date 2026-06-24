<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Models\Company;
use App\Models\Plan;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CreateCheckoutSession
{
    public function handle(Company $company, Plan $plan, string $interval = 'monthly'): Session
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $priceId = $interval === 'monthly'
            ? $plan->stripe_price_id_monthly
            : $plan->stripe_price_id_yearly;

        return Session::create([
            'mode'       => 'subscription',
            'customer'   => $company->stripe_customer_id,
            'line_items' => [[
                'price'    => $priceId,
                'quantity' => 1,
            ]],
            'success_url' => route('admin.billing.index').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('admin.billing.index'),
        ]);
    }
}
