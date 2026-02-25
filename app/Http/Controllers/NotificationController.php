<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationBulkReadRequest;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * List notifications for current user.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Notification::class);

        $user = auth()->user();

        $notifications = $user->notifications()
            ->with('user') // creator
            ->orderByDesc('notifications.created_at')
            ->paginate(20);

        // Unread count (pivot based)
        $unreadCount = $user->notifications()
            ->wherePivotNull('read_at')
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function markRead(Notification $notification): RedirectResponse
    {
        $this->authorize('markRead', $notification);

        $notification->markAsReadFor(auth()->user());

        return redirect()
            ->route('notifications.index')
            ->with('success', 'اعلان خوانده شد.');
    }

    /**
     * Mark one notification as unread.
     */
    public function markUnread(Notification $notification): RedirectResponse
    {
        $this->authorize('markUnread', $notification);

        $notification->markAsUnreadFor(auth()->user());

        return redirect()
            ->route('notifications.index')
            ->with('success', 'اعلان به عنوان خوانده نشده علامت گذاری شد.');
    }

    /**
     * Bulk mark notifications as read.
     */
    public function bulkRead(NotificationBulkReadRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $ids = $request->validated()['notification_ids'];

        // Only update pivots that belong to this user
        $user->notifications()->updateExistingPivot($ids, [
            'read_at' => now('UTC'),
        ]);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'اعلان‌ها به عنوان خوانده شده علامت گذاری شدند.');
    }
}