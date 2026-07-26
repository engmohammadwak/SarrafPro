<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ShopController extends Controller {

    public function index() {
        $shops = Shop::with('creator')->latest()->paginate(20);
        return view('superadmin.shops.index', compact('shops'));
    }

    public function create() {
        return view('superadmin.shops.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'username'       => ['nullable','string','max:50','alpha_dash',
                                  Rule::unique('shops','username')->whereNull('deleted_at')],
            'license_number' => 'nullable|string|max:50',
            'phone'          => 'nullable|string|max:20',
            'email'          => ['nullable','email',
                                  Rule::unique('shops','email')->whereNull('deleted_at')],
            'city'           => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'admin_name'     => 'required|string|max:100',
            'admin_email'    => ['required','email',
                                  Rule::unique('users','email')->whereNull('deleted_at')],
            'admin_password' => 'required|min:6',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('shops/attachments', 'public');
        }

        $shop = Shop::create([
            'name'           => $data['name'],
            'name_en'        => $data['name_en'] ?? null,
            'username'       => $data['username'] ?? null,
            'license_number' => $data['license_number'] ?? null,
            'phone'          => $data['phone'] ?? null,
            'email'          => $data['email'] ?? null,
            'city'           => $data['city'] ?? null,
            'notes'          => $data['notes'] ?? null,
            'attachment'     => $attachmentPath,
            'status'         => 'active',
            'created_by'     => auth()->id(),
        ]);

        User::create([
            'name'       => $data['admin_name'],
            'email'      => $data['admin_email'],
            'password'   => bcrypt($data['admin_password']),
            'role'       => 'shop_admin',
            'shop_id'    => $shop->id,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ إنشاء المحل بنجاح');
    }

    public function show(Shop $shop) {
        $shop->load('creator');
        return view('superadmin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop) {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop) {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'username'       => ['nullable','string','max:50','alpha_dash',
                                  Rule::unique('shops','username')->ignore($shop->id)->whereNull('deleted_at')],
            'license_number' => 'nullable|string|max:50',
            'phone'          => 'nullable|string|max:20',
            'email'          => ['nullable','email',
                                  Rule::unique('shops','email')->ignore($shop->id)->whereNull('deleted_at')],
            'city'           => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($shop->attachment) Storage::disk('public')->delete($shop->attachment);
            $data['attachment'] = $request->file('attachment')->store('shops/attachments', 'public');
        }

        $shop->update($data);
        return redirect()->route('superadmin.shops.show', $shop)->with('success', 'تمّ تحديث بيانات المحل');
    }

    public function destroy(Shop $shop) {
        if ($shop->attachment) Storage::disk('public')->delete($shop->attachment);
        $shop->delete();
        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ حذف المحل');
    }

    public function suspend(Shop $shop) {
        $shop->update(['status' => 'suspended']);
        return back()->with('success', 'تمّ تعليق المحل');
    }

    public function activate(Shop $shop) {
        $shop->update(['status' => 'active']);
        return back()->with('success', 'تمّ تفعيل المحل');
    }
}
