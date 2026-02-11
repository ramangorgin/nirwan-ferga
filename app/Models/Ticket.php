<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'priority',
        'status',
        'assigned_to'
    ];

    protected $casts = [
        'priority' => 'string',
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    // Helper Methods

    /**
     * Check if ticket is open
     */
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    /**
     * Check if ticket is closed
     */
    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Check if ticket is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if ticket is answered
     */
    public function isAnswered(): bool
    {
        return $this->status === 'answered';
    }

    /**
     * Check if ticket has been assigned
     */
    public function isAssigned(): bool
    {
        return $this->assigned_to !== null;
    }

    /**
     * Check if ticket is high priority
     */
    public function isHighPriority(): bool
    {
        return $this->priority === 'high';
    }

    /**
     * Check if ticket is medium priority
     */
    public function isMediumPriority(): bool
    {
        return $this->priority === 'medium';
    }

    /**
     * Check if ticket is low priority
     */
    public function isLowPriority(): bool
    {
        return $this->priority === 'low';
    }

    /**
     * Get the number of messages in this ticket
     */
    public function messageCount(): int
    {
        return $this->messages()->count();
    }

    /**
     * Get the latest message
     */
    public function latestMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Check if ticket has messages
     */
    public function hasMessages(): bool
    {
        return $this->messages()->exists();
    }

    /**
     * Get time since ticket was created
     */
    public function timeSinceCreated(): string
    {
        return $this->created_at->diffForHumans();
    }

}

