<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

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

    /**
     * تعليم إشعار واحد كمقروء ثم التوجيه لـ URL الإشعار
     */
    public function markOneRead(string $id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        $redirectUrl = route('agent.dashboard'); // fallback

        if ($notification) {
            $data = is_array($notification->data)
                ? $notification->data
                : json_decode($notification->data, true);

            $redirectUrl = $data['url'] ?? $redirectUrl;

            if (!$notification->read_at) {
                $notification->markAsRead();
            }
        }

        return redirect($redirectUrl);
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
