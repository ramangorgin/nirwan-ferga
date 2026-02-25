<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConversationStartRequest;
use App\Models\Conversation;
use App\Services\Conversations\ConversationService;
use App\Services\Messages\MessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationService $conversationService,
        protected MessageService $messageService
    ) {}

    /**
     * List conversations for current user.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Conversation::class);

        $user = auth()->user();

        $conversations = $this->conversationService->listForUser($user);

        // Optionally load last message in blade by calling $conv->lastMessage()
        // For performance later: use eager loading with subquery.

        return view('conversations.index', [
            'conversations' => $conversations,
        ]);
    }

    /**
     * Show a conversation messages.
     * Mark messages as read for current user.
     */
    public function show(Conversation $conversation): View
    {
        $this->authorize('view', $conversation);

        $user = auth()->user();

        // Mark as read when opening
        $this->messageService->markConversationAsRead($conversation, $user);

        // Load participants and messages
        $conversation->load(['student', 'teacher', 'course']);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->paginate(50);

        return view('conversations.show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'otherUser' => $conversation->otherParticipant($user),
        ]);
    }

    /**
     * Start a conversation (student -> teacher), optionally with first message.
     * Production-fast: if conversation exists, reuse it.
     */
    public function store(ConversationStartRequest $request): RedirectResponse
    {
        $this->authorize('create', Conversation::class);

        $user = auth()->user();
        $data = $request->validated();

        // Determine student_id:
        // - If current user is student: student_id = user
        // - If admin/teacher wants to start: you can expand later (not needed now)
        $studentId = (int) $user->id;

        $conversation = $this->conversationService->findOrCreate(
            studentId: $studentId,
            teacherId: (int) $data['teacher_id'],
            courseId: $data['course_id'] ?? null
        );

        // If the request includes message/attachment, send first message
        if (!empty($data['message']) || $request->file('attachment')) {
            $this->messageService->send(
                conversation: $conversation,
                sender: $user,
                data: ['message' => $data['message'] ?? null],
                attachment: $request->file('attachment')
            );
        }

        return redirect()
            ->route('conversations.show', $conversation)
            ->with('success', 'Conversation opened.');
    }
}