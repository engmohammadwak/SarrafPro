<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentController extends Controller {

    public function index() {
        $agents = User::where('role', 'agent')->latest()->paginate(20);
        return view('superadmin.agents.index', compact('agents'));
    }

    public function create() {
        return view('superadmin.agents.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'nullable|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
        $data['role']     = 'agent';
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ إضافة المندوب بنجاح');
    }

    public function show(User $agent) {
        return view('superadmin.agents.show', compact('agent'));
    }

    public function edit(User $agent) {
        return view('superadmin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent) {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => ['nullable','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($agent->id)],
            'email'    => ['required','email', Rule::unique('users','email')->ignore($agent->id)],
            'password' => 'nullable|min:6',
        ]);
        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = bcrypt($data['password']);
        $agent->update($data);
        return redirect()->route('superadmin.agents.show', $agent)->with('success', 'تمّ تحديث بيانات المندوب');
    }

    public function destroy(User $agent) {
        $agent->delete();
        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ حذف المندوب');
    }
}
