<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'username'       => ['required','string','max:50','alpha_dash',
                                  Rule::unique('shops','username')->withoutTrashed(),
                                  Rule::unique('users','username')],
            'license_number' => 'nullable|string|max:50',
            'phone'          => 'nullable|string|max:20',
            'email'          => ['nullable','email',
                                  Rule::unique('shops','email')->withoutTrashed(),
                                  Rule::unique('users','email')],
            'city'           => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'admin_name'     => 'required|string|max:100',
            'admin_email'    => ['required','email', Rule::unique('users','email')],
            'admin_password' => 'required|min:6',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('shops/attachments', 'public');
        }

        DB::transaction(function () use ($data, $attachmentPath) {
            $shop = Shop::create([
                'name'           => $data['name'],
                'name_en'        => $data['name_en'] ?? null,
                'username'       => $data['username'],
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
                'username'   => $data['username'],
                'password'   => bcrypt($data['admin_password']),
                'role'       => 'shop_admin',
                'shop_id'    => $shop->id,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ إنشاء المحل بنجاح');
    }

    public function show(Shop $shop) {
        $shop->load('creator','updater','admin');
        return view('superadmin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop) {
        return view('superadmin.shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop) {
        $data = $request->validate([
            'name'           => 'required|string|max:100',
            'name_en'        => 'nullable|string|max:100',
            'username'       => ['required','string','max:50','alpha_dash',
                                  Rule::unique('shops','username')->ignore($shop->id)->withoutTrashed(),
                                  Rule::unique('users','username')->ignore($shop->admin?->id)],
            'license_number' => 'nullable|string|max:50',
            'phone'          => 'nullable|string|max:20',
            'email'          => ['nullable','email',
                                  Rule::unique('shops','email')->ignore($shop->id)->withoutTrashed()],
            'city'           => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
            'attachment'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($shop->attachment) Storage::disk('public')->delete($shop->attachment);
            $data['attachment'] = $request->file('attachment')->store('shops/attachments', 'public');
        }

        // سجّل مَن عمل التحديث
        $data['updated_by'] = auth()->id();

        DB::transaction(function () use ($shop, $data, $request) {
            $shop->update($data);

            if ($shop->admin) {
                $adminData = ['username' => $data['username']];
                if ($request->filled('admin_name'))     $adminData['name']     = $request->admin_name;
                if ($request->filled('admin_email'))    $adminData['email']    = $request->admin_email;
                if ($request->filled('admin_password')) $adminData['password'] = bcrypt($request->admin_password);
                $shop->admin->update($adminData);
            }
        });

        return redirect()->route('superadmin.shops.show', $shop)->with('success', 'تمّ تحديث بيانات المحل');
    }

    public function destroy(Shop $shop) {
        if ($shop->attachment) {
            Storage::disk('public')->delete($shop->attachment);
        }
        User::where('shop_id', $shop->id)->delete();
        $shop->delete();
        return redirect()->route('superadmin.shops.index')->with('success', 'تمّ حذف المحل وجميع مستخدميه');
    }

    public function suspend(Shop $shop) {
        $shop->update(['status' => 'suspended', 'updated_by' => auth()->id()]);
        return back()->with('success', 'تمّ تعليق المحل');
    }

    public function activate(Shop $shop) {
        $shop->update(['status' => 'active', 'updated_by' => auth()->id()]);
        return back()->with('success', 'تمّ تفعيل المحل');
    }
}
