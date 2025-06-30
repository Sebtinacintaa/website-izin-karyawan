<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi pengguna saat ini.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');

        $user = Auth::user();
        $query = $user->notifications();

        if ($type === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Menandai semua notifikasi sebagai telah dibaca.
     */
    public function clear(): \Illuminate\Http\JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }

    /**
     * Polling notifikasi via AJAX dengan filter: all, new, unread
     */
    public function poll(Request $request, $userId): \Illuminate\Http\JsonResponse
    {
        $user = User::find($userId);
        abort_if(!$user, 404, 'User tidak ditemukan');

        $type = $request->query('type', 'all');
        $query = $user->notifications();

        switch ($type) {
            case 'unread':
                $query->whereNull('read_at');
                break;
            case 'new':
                $query->whereNull('read_at')->latest()->limit(10);
                break;
            case 'all':
            default:
                $query->latest();
                break;
        }

        $notifications = $query->get();

        $result = $notifications->map(function ($notif) {
            return [
                'id' => $notif->id,
                'title' => $notif->data['title'] ?? 'Tanpa Judul',
                'message' => $notif->data['message'] ?? 'Tidak ada pesan.',
                'read' => !is_null($notif->read_at),
                'date' => $notif->created_at->format('d/m/Y'),
            ];
        });

        return response()->json(['notifications' => $result]);
    }

    /**
     * Menghapus satu notifikasi tertentu milik user.
     */
    public function delete(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'not_found'], 404);
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function markAsRead($id): \Illuminate\Http\JsonResponse
    {
        $notification = DatabaseNotification::where('id', $id)
            ->where('notifiable_id', Auth::id())
            ->first();

        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan'], 404);
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }
}
