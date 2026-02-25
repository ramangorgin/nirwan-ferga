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
        'attachment_path',
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

    public function hasAttachment(): bool
    {
        return $this->attachment_path !== null;
    }

    public function isFromTicketCreator(): bool
    {
        return (int) $this->sender_id === (int) ($this->ticket?->user_id);
    }

    public function isFromAssignee(): bool
    {
        return (int) $this->sender_id === (int) ($this->ticket?->assigned_to);
    }

    public function isFromUser(User $user): bool
    {
        return (int) $this->sender_id === (int) $user->id;
    }

    public function timeSinceCreated(): string
    {
        return $this->created_at->diffForHumans();
    }
}