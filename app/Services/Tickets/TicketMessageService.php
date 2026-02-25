<?php

namespace App\Services\Tickets;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TicketMessageService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    /**
     * Send a message in a ticket.
     * Handles:
     * - attachment storage
     * - ticket status changes
     * - notification + sms to appropriate people
     */
    public function send(Ticket $ticket, User $sender, array $data, ?UploadedFile $attachment): TicketMessage
    {
        return DB::transaction(function () use ($ticket, $sender, $data, $attachment) {

            $hasMessage = !empty($data['message']);
            $hasAttachment = $attachment !== null;

            if (!$hasMessage && !$hasAttachment) {
                throw ValidationException::withMessages([
                    'message' => ['Message or attachment is required.'],
                ]);
            }

            // Create message first
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'sender_id' => $sender->id,
                'message' => $data['message'] ?? '',
                'attachment_path' => null,
            ]);

            // Store attachment if exists
            if ($attachment) {
                // Folder: tickets/{ticket_id}/messages/{message_id}
                $dir = "tickets/{$ticket->id}/messages/{$message->id}";
                $path = $attachment->store($dir, 'public');

                $message->update([
                    'attachment_path' => $path,
                ]);
            }

            // Status transition rules (production-fast + practical):
            // - If ticket was closed and user sends a message -> reopen
            // - If sender is admin -> set status to "answered" (unless explicitly "in_progress")
            // - If sender is user -> set status to "open" (or keep in_progress if admin is working)
            $this->applyStatusTransition($ticket, $sender);

            // Auto-assign admin on first admin reply (if ticket has no assigned_to)
            if ($sender->role === 'admin' && $ticket->assigned_to === null) {
                $ticket->update(['assigned_to' => $sender->id]);
            }

            // Notify recipients:
            // - If sender is ticket owner -> notify assigned admin, else notify all admins
            // - If sender is admin -> notify ticket owner
            $this->notifyAfterMessage($ticket->fresh(['user', 'assignedTo']), $sender, $message);

            return $message->fresh(['sender']);
        });
    }

    protected function applyStatusTransition(Ticket $ticket, User $sender): void
    {
        // Reopen if closed and user sends something
        if ($ticket->status === 'closed' && $sender->role !== 'admin') {
            $ticket->update(['status' => 'open']);
            return;
        }

        if ($sender->role === 'admin') {
            // Admin reply typically means "answered"
            if ($ticket->status !== 'closed') {
                $ticket->update(['status' => 'answered']);
            }
            return;
        }

        // Ticket owner message:
        // If admin marked in_progress keep it, else open
        if (!in_array($ticket->status, ['in_progress', 'closed'], true)) {
            $ticket->update(['status' => 'open']);
        }
    }

    protected function notifyAfterMessage(Ticket $ticket, User $sender, TicketMessage $message): void
    {
        $link = route('tickets.show', $ticket);

        if ($sender->role === 'admin') {
            // Notify ticket owner
            $this->notificationService->notifyUser(
                recipientUserId: (int) $ticket->user_id,
                creatorUserId: (int) $sender->id,
                title: 'Ticket پاسخ داده شد',
                body: mb_substr((string) $message->message, 0, 100),
                link: $link
            );

            $this->smsService->sendToUserId(
                (int) $ticket->user_id,
                "پاسخ جدید برای تیکت شما ثبت شد: {$ticket->subject}"
            );

            return;
        }

        // Sender is ticket owner (student/teacher)
        // Notify assigned admin if exists; otherwise notify all admins
        if ($ticket->assigned_to) {
            $this->notificationService->notifyUser(
                recipientUserId: (int) $ticket->assigned_to,
                creatorUserId: (int) $sender->id,
                title: 'New ticket message',
                body: mb_substr((string) $message->message, 0, 100),
                link: $link
            );

            $this->smsService->sendToUserId(
                (int) $ticket->assigned_to,
                "New message on ticket: {$ticket->subject}"
            );

            return;
        }

        // No assigned admin: notify all admins
        $admins = User::query()->where('role', 'admin')->pluck('id')->toArray();

        foreach ($admins as $adminId) {
            $this->notificationService->notifyUser(
                recipientUserId: (int) $adminId,
                creatorUserId: (int) $sender->id,
                title: 'New support ticket message',
                body: mb_substr((string) $message->message, 0, 100),
                link: $link
            );

            $this->smsService->sendToUserId(
                (int) $adminId,
                "New support ticket message: {$ticket->subject}"
            );
        }
    }

    /**
     * Optional: delete attachment if ever needed.
     */
    public function deleteAttachmentIfExists(TicketMessage $message): void
    {
        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }
    }
}