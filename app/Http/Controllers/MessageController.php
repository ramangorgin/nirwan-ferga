<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Models\Conversation;
use App\Services\Messages\MessageService;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function __construct(
        protected MessageService $messageService
    ) {}

    /**
     * Send a message in a conversation.
     */
    public function store(Conversation $conversation, MessageStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', [\App\Models\Message::class, $conversation]);

        $user = auth()->user();

        $this->messageService->send(
            conversation: $conversation,
            sender: $user,
            data: $request->validated(),
            attachment: $request->file('attachment')
        );

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Message sent.');
    }

    /**
     * Mark conversation messages as read (optional endpoint).
     */
    public function markRead(Conversation $conversation): RedirectResponse
    {
        $this->authorize('markRead', [\App\Models\Message::class, $conversation]);

        $this->messageService->markConversationAsRead($conversation, auth()->user());

        return redirect()
            ->route('conversations.show', $conversation);
    }
}