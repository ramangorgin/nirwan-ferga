<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    public function view(User $user, Notification $notification): bool
    {
        return $notification->users()->where('users.id', $user->id)->exists();
    }

    public function markRead(User $user, Notification $notification): bool
    {
        return $this->view($user, $notification);
    }

    public function markUnread(User $user, Notification $notification): bool
    {
        return $this->view($user, $notification);
    }
}