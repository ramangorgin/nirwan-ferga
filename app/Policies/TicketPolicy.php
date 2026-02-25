<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'admin') return true;

        return (int) $ticket->user_id === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Admin-only actions: assign, change status/priority.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->role === 'admin';
    }

    public function close(User $user, Ticket $ticket): bool
    {
        // user can close their own ticket, admin can close any
        if ($user->role === 'admin') return true;

        return (int) $ticket->user_id === (int) $user->id;
    }
}