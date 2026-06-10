<?php

declare(strict_types=1);

use App\Livewire\Onboarding;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

require __DIR__.'/settings.php';

Route::middleware('auth')->group(function (): void {
    Route::get('onboarding', Onboarding::class)
        ->middleware('onboarding')
        ->name('onboarding');
});

Route::middleware(['auth', 'customer'])->group(base_path('routes/app.php'));

Route::middleware('auth')->group(function (): void {
    Route::name('admin.')->middleware('admin')->prefix('admin')->group(base_path('routes/admin.php'));
});
