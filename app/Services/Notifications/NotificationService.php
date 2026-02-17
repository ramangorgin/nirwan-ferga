<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    public function notifyUser(int $recipientUserId, int $creatorUserId, string $title, ?string $body = null, ?string $link = null): Notification
    {
        return DB::transaction(function () use ($recipientUserId, $creatorUserId, $title, $body, $link) {

            $notification = Notification::create([
                'user_id' => $creatorUserId, // creator/sender
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);

            // pivot = recipients + per-user read state
            $notification->users()->attach($recipientUserId, [
                'read_at' => null,
            ]);

            return $notification;
        });
    }
}
