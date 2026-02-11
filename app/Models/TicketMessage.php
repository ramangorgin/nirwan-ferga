<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message',
        'attachment_path'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Helper Methods

    /**
     * Check if message has attachment
     */
    public function hasAttachment(): bool
    {
        return $this->attachment_path !== null;
    }

    /**
     * Check if message is from ticket creator
     */
    public function isFromTicketCreator(): bool
    {
        return $this->sender_id === $this->ticket->user_id;
    }

    /**
     * Check if message is from ticket assignee
     */
    public function isFromAssignee(): bool
    {
        return $this->sender_id === $this->ticket->assigned_to;
    }

    /**
     * Check if message is from user
     */
    public function isFromUser(User $user): bool
    {
        return $this->sender_id === $user->id;
    }

    /**
     * Get time since message was created
     */
    public function timeSinceCreated(): string
    {
        return $this->created_at->diffForHumans();
    }

}
