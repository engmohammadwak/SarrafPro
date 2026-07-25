<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('super-admin')->middleware(['web', 'super_admin'])->group(function () {
    Route::resource('shops', ShopController::class);
    Route::patch('shops/{shop}/suspend',  [ShopController::class, 'suspend'])->name('superadmin.shops.suspend');
    Route::patch('shops/{shop}/activate', [ShopController::class, 'activate'])->name('superadmin.shops.activate');
});
