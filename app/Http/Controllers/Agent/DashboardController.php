<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;

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
        $agents        = $this->agentRecords();
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
        // تعيين الكل كمقروء عند فتح الصفحة
        auth()->user()->unreadNotifications->markAsRead();

        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('agent.notifications', compact('notifications'));
    }

    public function reports()
    {
        return view('agent.reports');
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('agent.notifications');
    }
}
