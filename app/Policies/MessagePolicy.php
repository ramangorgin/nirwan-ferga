<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Sending a message is allowed only if user is a participant
     * (or admin).
     */
    public function create(User $user, Conversation $conversation): bool
    {
        if ($user->role === 'admin') return true;

        return (int) $conversation->student_id === (int) $user->id
            || (int) $conversation->teacher_id === (int) $user->id;
    }

    /**
     * Marking as read also requires participation.
     */
    public function markRead(User $user, Conversation $conversation): bool
    {
        return $this->create($user, $conversation);
    }

    /**
     * Viewing an individual message: same as viewing conversation.
     */
    public function view(User $user, Message $message): bool
    {
        if ($user->role === 'admin') return true;

        $conv = $message->conversation;

        if (!$conv) return false;

        return (int) $conv->student_id === (int) $user->id
            || (int) $conv->teacher_id === (int) $user->id;
    }
}