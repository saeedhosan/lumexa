<?php

declare(strict_types=1);

use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SettingController;
use Illuminate\Support\Facades\Route;

/**
 * Here's defined user routes
 */
Route::resource('settings', SettingController::class);
Route::resource('profile', ProfileController::class);
