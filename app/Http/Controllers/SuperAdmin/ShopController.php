<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('admin')->latest()->paginate(20);
        return view('superadmin.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('superadmin.shops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:100',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name'     => $validated['admin_name'],
            'email'    => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'role'     => 'admin',
        ]);

        $shop = Shop::create([
            'name'           => $validated['name'],
            'name_en'        => $validated['name_en'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'phone'          => $validated['phone'] ?? null,
            'email'          => $validated['email'] ?? null,
            'city'           => $validated['city'] ?? null,
            'country'        => $validated['country'] ?? 'OM',
            'status'         => 'active',
            'admin_id'       => $admin->id,
        ]);

        $admin->update(['shop_id' => $shop->id]);

        return redirect()->route('superadmin.shops.index')
            ->with('success', 'تم إنشاء المحل وحساب المدير بنجاح.');
    }

    public function show(Shop $shop)
    {
        $shop->load('admin');
        return view('superadmin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        $shop->load('admin');
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        $admin = $shop->admin;

        $rules = [
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'city'           => 'nullable|string|max:100',
            'status'         => 'required|in:active,suspended,pending',
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|unique:users,email,' . ($admin?->id ?? 0),
            'admin_password' => 'nullable|string|min:8',
        ];

        $validated = $request->validate($rules);

        // Update shop
        $shop->update([
            'name'           => $validated['name'],
            'name_en'        => $validated['name_en'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'phone'          => $validated['phone'] ?? null,
            'city'           => $validated['city'] ?? null,
            'status'         => $validated['status'],
        ]);

        // Update admin user
        if ($admin) {
            $adminData = [
                'name'  => $validated['admin_name'],
                'email' => $validated['admin_email'],
            ];
            if (!empty($validated['admin_password'])) {
                $adminData['password'] = Hash::make($validated['admin_password']);
            }
            $admin->update($adminData);
        }

        return redirect()->route('superadmin.shops.show', $shop)
            ->with('success', 'تم تحديث بيانات المحل والمدير بنجاح.');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('superadmin.shops.index')
            ->with('success', 'تم حذف المحل بنجاح.');
    }

    public function suspend(Shop $shop)
    {
        $shop->update(['status' => 'suspended']);
        return back()->with('success', 'تم تعليق المحل بنجاح.');
    }

    public function activate(Shop $shop)
    {
        $shop->update(['status' => 'active']);
        return back()->with('success', 'تم تفعيل المحل بنجاح.');
    }
}
