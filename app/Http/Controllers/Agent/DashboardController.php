<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;

class DashboardController extends Controller
{
    private function agentRecords()
    {
        return Agent::with('shop')
            ->where('user_id', auth()->id())
            ->get();
    }

    public function index()
    {
        $agents       = $this->agentRecords();
        $approvedCount = $agents->where('link_status', 'approved')->count();
        $pendingCount  = $agents->where('link_status', 'pending')->count();
        $rejectedCount = $agents->where('link_status', 'rejected')->count();

        return view('agent.dashboard', compact(
            'agents', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    public function transactions()
    {
        return view('agent.transactions');
    }

    public function notifications()
    {
        return view('agent.notifications');
    }

    public function reports()
    {
        return view('agent.reports');
    }
}
