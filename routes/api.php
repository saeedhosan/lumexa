<?php

declare(strict_types=1);

use App\Http\Controllers\Api\LeadController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::apiResource('leads', LeadController::class)->only(['index', 'show']);
});
