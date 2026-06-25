<?php

declare(strict_types=1);

use App\Http\Controllers\HealthController;
use App\Livewire\Onboarding;
use Illuminate\Support\Facades\Route;

Route::get('health', HealthController::class)->name('health');

Route::view('/', 'welcome')->name('home');

require __DIR__.'/settings.php';

Route::middleware('auth')->group(function (): void {
    Route::livewire('onboarding', Onboarding::class)
        ->middleware('onboarding')
        ->name('onboarding');
});

Route::middleware(['auth', 'customer'])->group(base_path('routes/app.php'));

Route::middleware('auth')->name('admin.')->prefix('admin')->group(base_path('routes/admin.php'));
