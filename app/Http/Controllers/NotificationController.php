<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetch(Request $request)
    {
        $items = Auth::user()->userNotifications()
            ->orderByDesc('created_at')
            ->limit(25)
            ->get();

        return response()->json([
            'ok' => true,
            'unread' => Auth::user()->unreadNotificationsCount(),
            'items' => $items->map(function (UserNotification $n) {
                $data = is_array($n->data) ? $n->data : [];

                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'body' => $n->body,
                    'type' => $n->type,
                    'url' => $data['url'] ?? null,
                    'icon' => $data['icon'] ?? null,
                    'unread' => $n->isUnread(),
                    'waktu' => $n->created_at?->diffForHumans() ?? '',
                ];
            }),
        ]);
    }

    public function read(Request $request, UserNotification $notification)
    {
        abort_unless((int) $notification->user_id === (int) Auth::id(), 403);

        $notification->markAsRead();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'unread' => Auth::user()->unreadNotificationsCount()]);
        }

        return back();
    }

    public function readAll(Request $request)
    {
        Auth::user()->unreadUserNotifications()->update(['read_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['ok' => true, 'unread' => 0]);
        }

        return back();
    }

    public function index()
    {
        $notifications = Auth::user()->userNotifications()
            ->orderByDesc('created_at')
            ->simplePaginate(20);

        Auth::user()->unreadUserNotifications()->update(['read_at' => now()]);

        return view('dashboard.notifikasi', [
            'notifications' => $notifications,
        ]);
    }
}