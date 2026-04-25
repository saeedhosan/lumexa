<?php

declare(strict_types=1);

use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/invite/{invite}', [InviteController::class, 'show'])
    ->name('invite.accept')
    ->middleware('signed');

Route::post('/invite/{invite}/accept', [InviteController::class, 'accept'])
    ->name('invite.process');

require __DIR__.'/settings.php';
require __DIR__.'/dashboard.php';

Route::middleware('auth')->group(function (): void {
    Route::name('admin.')->middleware('admin')->prefix('admin')->group(base_path('routes/admin.php'));
});
