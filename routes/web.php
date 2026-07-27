<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\SuperAdmin\ShopController as SuperAdminShop;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUser;
use App\Http\Controllers\SuperAdmin\AgentController as SuperAdminAgent;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboard;

// تسجيل دخول موحد
Route::get('/',       [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/admin/login',       fn() => redirect()->route('login'));
Route::get('/super-admin/login', fn() => redirect()->route('login'));
Route::get('/agent/login',       fn() => redirect()->route('login'));

// Super Admin Routes
Route::prefix('super-admin')->name('superadmin.')->middleware('super_admin')->group(function () {
    Route::get('dashboard', [SuperAdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('shops', SuperAdminShop::class);
    Route::patch('shops/{shop}/suspend',  [SuperAdminShop::class, 'suspend'])->name('shops.suspend');
    Route::patch('shops/{shop}/activate', [SuperAdminShop::class, 'activate'])->name('shops.activate');
    Route::resource('users',  SuperAdminUser::class);
    Route::resource('agents', SuperAdminAgent::class);
    Route::patch('agents/{agent}/suspend',  [SuperAdminAgent::class, 'suspend'])->name('agents.suspend');
    Route::patch('agents/{agent}/activate', [SuperAdminAgent::class, 'activate'])->name('agents.activate');
    Route::delete('agents/{agent}/attachment', [SuperAdminAgent::class, 'deleteAttachment'])->name('agents.attachment.destroy');
});

// Admin (Shop) Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('staff', StaffController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('agents', AgentController::class);
    Route::get('agents/check-user',             [AgentController::class, 'checkUser'])->name('agents.check-user');
    Route::patch('agents/{agent}/approve-link', [AgentController::class, 'approveLink'])->name('agents.approve-link');
    Route::patch('agents/{agent}/reject-link',  [AgentController::class, 'rejectLink'])->name('agents.reject-link');
    Route::get('transactions',               [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/create',        [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('transactions',              [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Agent Routes
Route::prefix('agent')->name('agent.')->middleware('agent')->group(function () {
    Route::get('dashboard', [AgentDashboard::class, 'index'])->name('dashboard');
});
