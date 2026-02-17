<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Notify a user with a message
     * 
     * @param int $recipientUserId User ID who receives the notification
     * @param int $creatorUserId User ID who creates the notification
     * @param string $title Notification title
     * @param string|null $body Notification body/message
     * @param string|null $link Link to navigate to
     * @return Notification
     */
    public function notifyUser(
        int $recipientUserId,
        int $creatorUserId,
        string $title,
        ?string $body = null,
        ?string $link = null
    ): Notification {
        return DB::transaction(function () use ($recipientUserId, $creatorUserId, $title, $body, $link) {

            // Create notification with creator as the author
            $notification = Notification::create([
                'user_id' => $creatorUserId,
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);

            // Attach the recipient user with unread state
            $notification->users()->attach($recipientUserId, [
                'read_at' => null,
            ]);

            return $notification;
        });
    }

    /**
     * Notify multiple users at once
     * 
     * @param array $recipientUserIds Array of user IDs to receive notification
     * @param int $creatorUserId User ID who creates the notification
     * @param string $title Notification title
     * @param string|null $body Notification body/message
     * @param string|null $link Link to navigate to
     * @return Notification
     */
    public function notifyMultiple(
        array $recipientUserIds,
        int $creatorUserId,
        string $title,
        ?string $body = null,
        ?string $link = null
    ): Notification {
        return DB::transaction(function () use ($recipientUserIds, $creatorUserId, $title, $body, $link) {

            $notification = Notification::create([
                'user_id' => $creatorUserId,
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);

            // Attach all recipients with unread state
            $attachData = array_fill_keys($recipientUserIds, ['read_at' => null]);
            $notification->users()->attach($attachData);

            return $notification;
        });
    }
}

