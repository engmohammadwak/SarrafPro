<?php
namespace App\Http\Controllers\Agent;
use App\Http\Controllers\Controller;
use App\Models\Agent;

class DashboardController extends Controller {
    public function index() {
        $user  = auth()->user();
        // المندوبين المرتبطين بهذا الحساب
        $agents       = Agent::where('user_id', $user->id)->with('shop')->get();
        $pendingCount = $agents->where('link_status','pending')->count();
        $approvedCount= $agents->where('link_status','approved')->count();
        return view('agent.dashboard', compact('agents','pendingCount','approvedCount'));
    }
}
