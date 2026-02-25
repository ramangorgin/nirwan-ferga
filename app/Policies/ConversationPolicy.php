<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Admin can view all.
     * Teacher can view their conversations.
     * Student can view their conversations.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    public function view(User $user, Conversation $conversation): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $conversation->teacher_id === (int) $user->id;
        }

        if ($user->role === 'student') {
            return (int) $conversation->student_id === (int) $user->id;
        }

        return false;
    }

    /**
     * Starting a conversation:
     * - student: allowed
     * - admin: allowed (optional)
     * - teacher: allowed (optional)
     *
     * If you want ONLY students to start, restrict to student only.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['student', 'admin', 'teacher'], true);
    }
}