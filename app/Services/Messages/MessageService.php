<?php

namespace App\Services\Messages;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MessageService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    /**
     * Send a message in a conversation. Stores optional attachment.
     * Also sends notification + SMS to the other participant.
     */
    public function send(Conversation $conversation, User $sender, array $data, ?UploadedFile $attachment): Message
    {
        return DB::transaction(function () use ($conversation, $sender, $data, $attachment) {

            // Ensure sender is participant (or admin)
            if ($sender->role !== 'admin'
                && (int) $conversation->student_id !== (int) $sender->id
                && (int) $conversation->teacher_id !== (int) $sender->id
            ) {
                throw ValidationException::withMessages([
                    'conversation' => ['You are not allowed to send message in this conversation.'],
                ]);
            }

            // At least message or attachment
            $hasMessage = !empty($data['message']);
            $hasAttachment = $attachment !== null;

            if (!$hasMessage && !$hasAttachment) {
                throw ValidationException::withMessages([
                    'message' => ['Message or attachment is required.'],
                ]);
            }

            // Create message first (attachment stored after we have message id)
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'message' => $data['message'] ?? null,
                'attachment_path' => null,
                'is_read' => false,
                'read_at' => null,
            ]);

            // Store attachment if exists
            if ($attachment) {
                // Folder: conversations/{conversation_id}/messages/{message_id}
                $dir = "conversations/{$conversation->id}/messages/{$message->id}";
                $path = $attachment->store($dir, 'public');

                $message->update([
                    'attachment_path' => $path,
                ]);
            }

            // Notify the other participant
            $receiverId = $this->otherParticipantId($conversation, $sender);

            // Notification link (adjust route name to your routes)
            $link = route('conversations.show', $conversation);

            $title = 'New message';
            $body  = $hasMessage
                ? mb_substr((string) $data['message'], 0, 80)
                : 'New attachment sent';

            $this->notificationService->notifyUser(
                recipientUserId: (int) $receiverId,
                creatorUserId: (int) $sender->id,
                title: $title,
                body: $body,
                link: $link
            );

            $this->smsService->sendToUserId(
                (int) $receiverId,
                "You received a new message."
            );

            return $message->fresh(['sender']);
        });
    }

    /**
     * Mark all messages as read for viewer.
     * Sets read_at in UTC.
     */
    public function markConversationAsRead(Conversation $conversation, User $viewer): int
    {
        // Mark all messages not sent by viewer as read
        return $conversation->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $viewer->id)
            ->update([
                'is_read' => true,
                'read_at' => now('UTC'),
            ]);
    }

    /**
     * Helper: get other participant id.
     */
    protected function otherParticipantId(Conversation $conversation, User $sender): int
    {
        if ((int) $conversation->student_id === (int) $sender->id) {
            return (int) $conversation->teacher_id;
        }

        return (int) $conversation->student_id;
    }

    /**
     * Optional: delete a message attachment (if ever needed).
     */
    public function deleteAttachmentIfExists(Message $message): void
    {
        if ($message->attachment_path) {
            Storage::disk('public')->delete($message->attachment_path);
        }
    }
}