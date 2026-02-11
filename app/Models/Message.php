<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'attachment_path',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Helper Methods

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return $this->is_read === true;
    }

    /**
     * Check if message is unread
     */
    public function isUnread(): bool
    {
        return $this->is_read === false;
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    /**
     * Check if message has attachment
     */
    public function hasAttachment(): bool
    {
        return $this->attachment_path !== null;
    }

    /**
     * Check if message is from user
     */
    public function isFromUser(User $user): bool
    {
        return $this->sender_id === $user->id;
    }

    /**
     * Get time since message was sent
     */
    public function timeSinceSent(): string
    {
        return $this->created_at->diffForHumans();
    }

}

