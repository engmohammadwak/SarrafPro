<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('admin')->latest()->paginate(15);
        return view('admin.shops.index', compact('shops'));
    }

    public function create()
    {
        $admins = User::where('role', 'shop_admin')->where('is_active', true)->get();
        return view('admin.shops.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'license_number' => 'nullable|string|unique:shops',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:10',
            'status'         => 'required|in:active,suspended,pending',
            'logo'           => 'nullable|image|max:2048',
        ], [
            'name.required' => 'اسم المحل مطلوب',
            'status.required' => 'الحالة مطلوبة',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        Shop::create($validated);

        return redirect()->route('admin.shops.index')->with('success', 'تم إضافة المحل بنجاح ✓');
    }

    public function show(Shop $shop)
    {
        return view('admin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        $admins = User::where('role', 'shop_admin')->where('is_active', true)->get();
        return view('admin.shops.edit', compact('shop', 'admins'));
    }

    public function update(Request $request, Shop $shop)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'license_number' => 'nullable|string|unique:shops,license_number,' . $shop->id,
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
            'city'           => 'nullable|string|max:100',
            'country'        => 'nullable|string|max:10',
            'status'         => 'required|in:active,suspended,pending',
            'logo'           => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($shop->logo) Storage::disk('public')->delete($shop->logo);
            $validated['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }

        $shop->update($validated);

        return redirect()->route('admin.shops.index')->with('success', 'تم تحديث المحل بنجاح ✓');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->route('admin.shops.index')->with('success', 'تم حذف المحل');
    }

    public function toggleStatus(Shop $shop)
    {
        $shop->status = $shop->status === 'active' ? 'suspended' : 'active';
        $shop->save();
        $msg = $shop->status === 'active' ? 'تم تفعيل المحل' : 'تم تعليق المحل';
        return back()->with('success', $msg);
    }
}
