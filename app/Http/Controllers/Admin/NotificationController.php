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

        $url = route('admin.dashboard');
        if ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
            $url  = $data['url'] ?? $url;
            if (!$n->read_at) $n->markAsRead();
        }
        return redirect($url);
    }

    /** AJAX: آخر 5 إشعارات + عدد غير مقروءة */
    public function latest()
    {
        $user  = auth()->user();
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
