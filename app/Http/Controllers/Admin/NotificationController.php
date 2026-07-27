<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $notifications = auth()->user()->notifications()->latest()->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        if ($request->wantsJson()) return response()->json(['success' => true]);
        return back();
    }

    public function markOneRead(string $id)
    {
        $n = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->first();

        $role = auth()->user()->role;

        // الرابط الافتراضي حسب الدور
        $defaultUrl = match($role) {
            'super_admin'          => route('superadmin.dashboard'),
            'agent','cooperation'  => route('agent.dashboard'),
            default                => route('admin.agents.index'),
        };

        $url = $defaultUrl;

        if ($n) {
            $data      = is_array($n->data) ? $n->data : json_decode($n->data, true);
            $storedUrl = $data['url'] ?? null;

            if ($storedUrl && $this->urlMatchesRole($storedUrl, $role)) {
                $url = $storedUrl;
            }

            if (!$n->read_at) $n->markAsRead();
        }

        return redirect($url);
    }

    /**
     * تحقق أن الرابط ينتمي لمجال دور المستخدم
     */
    private function urlMatchesRole(string $url, string $role): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? $url;

        return match($role) {
            'super_admin'          => str_starts_with($path, '/super-admin/'),
            'agent','cooperation'  => str_starts_with($path, '/agent/'),
            default                => str_starts_with($path, '/admin/'),
        };
    }

    /** AJAX: آخر 5 إشعارات + عدد غير مقروءة */
    public function latest()
    {
        $user   = auth()->user();
        $unread = $user->unreadNotifications()->count();
        $items  = $user->notifications()->latest()->take(5)->get()->map(function ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            return [
                'id'      => $n->id,
                'type'    => $data['type']    ?? 'info',
                'title'   => $data['title']   ?? '',
                'message' => $data['message'] ?? '',
                'url'     => route('admin.notifications.read', $n->id),
                'read'    => !is_null($n->read_at),
                'time'    => $n->created_at->diffForHumans(),
            ];
        });
        return response()->json(['unread' => $unread, 'items' => $items]);
    }
}
