<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller {
    private function shopId() { return auth()->user()->shop_id; }

    public function index() {
        $agents = Agent::where('shop_id', $this->shopId())->latest()->paginate(20);
        return view('admin.agents.index', compact('agents'));
    }

    public function create() { return view('admin.agents.create'); }

    public function store(Request $request) {
        $v = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
        ]);
        Agent::create(array_merge($v, ['shop_id' => $this->shopId()]));
        return redirect()->route('admin.agents.index')->with('success', 'تم إضافة المندوب.');
    }

    public function edit(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $v = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'company' => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
        ]);
        $agent->update($v);
        return redirect()->route('admin.agents.index')->with('success', 'تم تحديث بيانات المندوب.');
    }

    public function destroy(Agent $agent) {
        abort_if($agent->shop_id !== $this->shopId(), 403);
        $agent->delete();
        return back()->with('success', 'تم حذف المندوب.');
    }
}
