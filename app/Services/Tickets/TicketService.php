<?php

namespace App\Services\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    /**
     * List tickets for user:
     * - admin sees all
     * - others see their own
     */
    public function listForUser(User $user)
    {
        $q = Ticket::query()
            ->with(['user', 'assignedTo'])
            ->latest();

        if ($user->role !== 'admin') {
            $q->where('user_id', $user->id);
        }

        return $q->paginate(20);
    }

    /**
     * Admin update: status/priority/assigned_to
     */
    public function adminUpdate(Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($ticket, $data) {
            $ticket->update($data);
            return $ticket->fresh(['user', 'assignedTo']);
        });
    }

    /**
     * Close ticket:
     * - user can close their own
     * - admin can close any
     */
    public function close(Ticket $ticket): Ticket
    {
        return DB::transaction(function () use ($ticket) {
            $ticket->update(['status' => 'closed']);
            return $ticket->fresh();
        });
    }

    /**
     * Reopen ticket if needed (optional utility)
     */
    public function reopen(Ticket $ticket): Ticket
    {
        return DB::transaction(function () use ($ticket) {
            $ticket->update(['status' => 'open']);
            return $ticket->fresh();
        });
    }
}