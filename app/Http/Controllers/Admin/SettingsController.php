<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller {

    public function index() {
        $shop = auth()->user()->shop;
        $user = auth()->user();
        return view('admin.settings.index', compact('shop', 'user'));
    }

    public function update(Request $request) {
        $shop = auth()->user()->shop;
        $user = auth()->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'name_en'      => 'nullable|string|max:255',
            'phone'        => 'nullable|string|max:20',
            'city'         => 'nullable|string|max:100',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'email'        => ['nullable','email', Rule::unique('users','email')->ignore($user->id)],
            'username'     => ['nullable','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($user->id)],
            'current_password' => 'nullable|string',
            'new_password'     => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'          => 'اسم المحل مطلوب',
            'email.email'            => 'بريد غير صحيح',
            'email.unique'           => 'هذا البريد مستخدم مسبقاً',
            'username.unique'        => 'هذا الاسم مستخدم مسبقاً',
            'username.alpha_dash'    => 'الاسم يجب أن يحتوي على حروف وأرقام وشرطة سفلية فقط',
            'new_password.min'       => 'كلمة المرور يجب أن تكون على الأقل ٦ أحرف',
            'new_password.confirmed' => 'تأكيد كلمة المرور غير مطابق',
            'logo.image'             => 'يجب أن يكون اللوجو صورة',
            'logo.max'               => 'حجم اللوجو أكثر من 2MB',
        ]);

        // تحديث بيانات المحل
        $shopData = [
            'name'    => $request->name,
            'name_en' => $request->name_en,
            'phone'   => $request->phone,
            'city'    => $request->city,
        ];

        // لوجو المحل
        if ($request->hasFile('logo')) {
            if ($shop->logo) Storage::disk('public')->delete($shop->logo);
            $shopData['logo'] = $request->file('logo')->store('shops/logos', 'public');
        }
        if ($request->input('remove_logo')) {
            if ($shop->logo) Storage::disk('public')->delete($shop->logo);
            $shopData['logo'] = null;
        }

        $shop->update($shopData);

        // تحديث بيانات المستخدم
        $userData = [];
        if ($request->filled('email'))    $userData['email']    = $request->email;
        if ($request->filled('username')) $userData['username'] = $request->username;

        // تغيير كلمة السر
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
            $userData['password'] = bcrypt($request->new_password);
        }

        if (!empty($userData)) $user->update($userData);

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
