<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Billing\HandleStripeWebhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StripeWebhookController
{
    public function __invoke(Request $request, HandleStripeWebhook $handler): Response
    {
        return $handler->handle(
            $request->getContent(),
            (string) $request->header('Stripe-Signature', '')
        );
    }
}
