<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Models\Company;
use Illuminate\Http\Response;
use Stripe\Event;
use Stripe\Stripe;
use Stripe\Webhook;

class HandleStripeWebhook
{
    public function handle(string $payload, string $signature): Response
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        match ($event->type) {
            Event::CUSTOMER_SUBSCRIPTION_UPDATED => $this->handleSubscriptionUpdated($event),
            Event::CUSTOMER_SUBSCRIPTION_DELETED => $this->handleSubscriptionDeleted($event),
            Event::INVOICE_PAYMENT_SUCCEEDED     => $this->handleInvoicePaid(),
            default                              => null,
        };

        return response('OK', 200);
    }

    private function handleSubscriptionUpdated(Event $event): void
    {
        $subscription = $event->data->object;

        Company::query()
            ->where('stripe_customer_id', $subscription->customer)
            ->update([
                'stripe_subscription_status' => $subscription->status,
            ]);
    }

    private function handleSubscriptionDeleted(Event $event): void
    {
        $subscription = $event->data->object;

        Company::query()
            ->where('stripe_customer_id', $subscription->customer)
            ->update([
                'stripe_subscription_id'     => null,
                'stripe_subscription_status' => 'canceled',
            ]);
    }

    private function handleInvoicePaid(): void
    {
        // Log or update last payment date
    }
}
