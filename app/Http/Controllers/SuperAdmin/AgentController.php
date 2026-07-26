<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgentController extends Controller {

    public function index() {
        $agents = User::with('creator')->where('role','agent')->latest()->paginate(20);
        return view('superadmin.agents.index', compact('agents'));
    }

    public function create() {
        return view('superadmin.agents.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => 'nullable|string|max:50|alpha_dash|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'notes'      => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('agents/attachments','public');
        }

        User::create([
            'name'       => $data['name'],
            'username'   => $data['username'] ?? null,
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'role'       => 'agent',
            'notes'      => $data['notes'] ?? null,
            'attachment' => $attachmentPath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ إضافة المندوب بنجاح');
    }

    public function show(User $agent) {
        $agent->load('creator');
        return view('superadmin.agents.show', compact('agent'));
    }

    public function edit(User $agent) {
        return view('superadmin.agents.edit', compact('agent'));
    }

    public function update(Request $request, User $agent) {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'username'   => ['nullable','string','max:50','alpha_dash', Rule::unique('users','username')->ignore($agent->id)],
            'email'      => ['required','email', Rule::unique('users','email')->ignore($agent->id)],
            'password'   => 'nullable|min:6',
            'notes'      => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($agent->attachment) \Storage::disk('public')->delete($agent->attachment);
            $data['attachment'] = $request->file('attachment')->store('agents/attachments','public');
        }

        if (empty($data['password'])) unset($data['password']);
        else $data['password'] = bcrypt($data['password']);

        $agent->update($data);
        return redirect()->route('superadmin.agents.show', $agent)->with('success', 'تمّ تحديث بيانات المندوب');
    }

    public function destroy(User $agent) {
        if ($agent->attachment) \Storage::disk('public')->delete($agent->attachment);
        $agent->delete();
        return redirect()->route('superadmin.agents.index')->with('success', 'تمّ حذف المندوب');
    }
}
