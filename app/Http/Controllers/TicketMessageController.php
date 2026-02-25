<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketMessageStoreRequest;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Services\Tickets\TicketMessageService;
use Illuminate\Http\RedirectResponse;

class TicketMessageController extends Controller
{
    public function __construct(
        protected TicketMessageService $ticketMessageService
    ) {}

    public function store(Ticket $ticket, TicketMessageStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', [TicketMessage::class, $ticket]);

        $this->ticketMessageService->send(
            ticket: $ticket,
            sender: auth()->user(),
            data: $request->validated(),
            attachment: $request->file('attachment')
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Message sent.');
    }
}