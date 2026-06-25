<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('stripe/webhook', StripeWebhookController::class)->name('stripe.webhook');

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::apiResource('leads', LeadController::class);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('services', ServiceController::class)->only(['index', 'show']);
});
