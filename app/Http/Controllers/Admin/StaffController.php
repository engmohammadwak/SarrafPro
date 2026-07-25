<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller {
    private function shop() { return auth()->user()->shop; }

    public function index() {
        $staff = Staff::with('user')->where('shop_id', $this->shop()->id)->latest()->paginate(20);
        return view('admin.staff.index', compact('staff'));
    }

    public function create() { return view('admin.staff.create'); }

    public function store(Request $request) {
        $v = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|max:100',
        ]);
        $user = User::create([
            'name'     => $v['name'],
            'email'    => $v['email'],
            'password' => Hash::make($v['password']),
            'role'     => 'staff',
            'shop_id'  => $this->shop()->id,
        ]);
        Staff::create(['shop_id' => $this->shop()->id, 'user_id' => $user->id, 'role' => $v['role']]);
        return redirect()->route('admin.staff.index')->with('success', 'تم إضافة الموظف بنجاح.');
    }

    public function edit(Staff $staff) {
        abort_if($staff->shop_id !== $this->shop()->id, 403);
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff) {
        abort_if($staff->shop_id !== $this->shop()->id, 403);
        $v = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$staff->user_id,
            'role'     => 'required|string|max:100',
            'password' => 'nullable|string|min:8',
        ]);
        $data = ['name' => $v['name'], 'email' => $v['email']];
        if (!empty($v['password'])) $data['password'] = Hash::make($v['password']);
        $staff->user->update($data);
        $staff->update(['role' => $v['role']]);
        return redirect()->route('admin.staff.index')->with('success', 'تم تحديث بيانات الموظف.');
    }

    public function destroy(Staff $staff) {
        abort_if($staff->shop_id !== $this->shop()->id, 403);
        $staff->user->delete();
        $staff->delete();
        return back()->with('success', 'تم حذف الموظف.');
    }
}
