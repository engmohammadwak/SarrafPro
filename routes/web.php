<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Logout route (used in superadmin layout)
Route::post('/logout', [\App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
