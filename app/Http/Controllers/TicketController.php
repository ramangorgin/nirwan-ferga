<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketAdminUpdateRequest;
use App\Http\Requests\TicketStoreRequest;
use App\Models\Ticket;
use App\Services\Tickets\TicketMessageService;
use App\Services\Tickets\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService,
        protected TicketMessageService $ticketMessageService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Ticket::class);

        $tickets = $this->ticketService->listForUser(auth()->user());

        return view('tickets.index', [
            'tickets' => $tickets,
            'enums' => [
                'priority' => ['low', 'medium', 'high'],
                'status' => ['open', 'in_progress', 'answered', 'closed'],
            ],
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Ticket::class);

        return view('tickets.create', [
            'enums' => [
                'priority' => ['low', 'medium', 'high'],
            ],
            'defaults' => [
                'priority' => 'medium',
            ],
        ]);
    }

    /**
     * Create ticket + first message (optional attachment).
     */
    public function store(TicketStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Ticket::class);

        $data = $request->validated();
        $user = auth()->user();

        // Create the ticket
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'subject' => $data['subject'],
            'priority' => $data['priority'] ?? 'medium',
            'status' => 'open',
            'assigned_to' => null,
        ]);

        // Create first ticket message using service (handles attachment + notifications)
        $this->ticketMessageService->send(
            ticket: $ticket,
            sender: $user,
            data: ['message' => $data['message'] ?? ''],
            attachment: $request->file('attachment')
        );

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['user', 'assignedTo']);

        $messages = $ticket->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->paginate(50);

        return view('tickets.show', [
            'ticket' => $ticket,
            'messages' => $messages,
            'enums' => [
                'priority' => ['low', 'medium', 'high'],
                'status' => ['open', 'in_progress', 'answered', 'closed'],
            ],
        ]);
    }

    /**
     * Admin updates ticket metadata (status/priority/assigned_to).
     */
    public function adminUpdate(TicketAdminUpdateRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorize('update', $ticket);

        $this->ticketService->adminUpdate($ticket, $request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated.');
    }

    /**
     * Close ticket (admin or owner).
     */
    public function close(Ticket $ticket): RedirectResponse
    {
        $this->authorize('close', $ticket);

        $this->ticketService->close($ticket);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket closed.');
    }
}