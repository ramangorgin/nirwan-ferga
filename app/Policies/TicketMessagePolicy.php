<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;

class TicketMessagePolicy
{
    /**
     * Can send message if:
     * - admin, or
     * - ticket owner
     */
    public function create(User $user, Ticket $ticket): bool
    {
        if ($user->role === 'admin') return true;

        return (int) $ticket->user_id === (int) $user->id;
    }

    public function view(User $user, TicketMessage $message): bool
    {
        if ($user->role === 'admin') return true;

        return (int) ($message->ticket?->user_id) === (int) $user->id;
    }
}