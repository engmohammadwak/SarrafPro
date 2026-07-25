<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_shops'     => Shop::count(),
            'active_shops'    => Shop::where('status', 'active')->count(),
            'suspended_shops' => Shop::where('status', 'suspended')->count(),
            'pending_shops'   => Shop::where('status', 'pending')->count(),
            'total_admins'    => User::where('role', 'shop_admin')->count(),
            'total_users'     => User::where('role', 'user')->count(),
        ];

        $recentShops = Shop::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentShops'));
    }
}
