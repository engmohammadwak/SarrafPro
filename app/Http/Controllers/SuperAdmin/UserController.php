<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    public function index() {
        $users = User::with('creator')->where('role', 'super_admin')->latest()->paginate(20);
        return view('superadmin.users.index', compact('users'));
    }

    public function create() {
        return view('superadmin.users.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        $data['role']       = 'super_admin';
        $data['password']   = bcrypt($data['password']);
        $data['created_by'] = auth()->id();
        User::create($data);
        return redirect()->route('superadmin.users.index')->with('success', 'تمّ إضافة المستخدم بنجاح');
    }

    public function show(User $user) {
        $user->load('creator');
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user) {
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => ['nullable','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($user->id)],
            'email'    => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'password' => 'nullable|min:6',
        ]);
        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = bcrypt($data['password']);
        $user->update($data);
        return redirect()->route('superadmin.users.show', $user)->with('success', 'تمّ تحديث بيانات المستخدم');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'تمّ حذف المستخدم');
    }
}
