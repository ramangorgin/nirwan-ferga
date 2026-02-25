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
        'assigned_to',
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
        // Order by created_at ascending for chat view
        return $this->hasMany(TicketMessage::class)->orderBy('created_at');
    }

    // Helper Methods

    public function isOpen(): bool { return $this->status === 'open'; }
    public function isClosed(): bool { return $this->status === 'closed'; }
    public function isInProgress(): bool { return $this->status === 'in_progress'; }
    public function isAnswered(): bool { return $this->status === 'answered'; }

    public function isAssigned(): bool { return $this->assigned_to !== null; }

    public function isHighPriority(): bool { return $this->priority === 'high'; }
    public function isMediumPriority(): bool { return $this->priority === 'medium'; }
    public function isLowPriority(): bool { return $this->priority === 'low'; }

    public function messageCount(): int
    {
        return $this->messages()->count();
    }

    public function latestMessage()
    {
        return $this->messages()->latest()->first();
    }

    public function hasMessages(): bool
    {
        return $this->messages()->exists();
    }

    public function timeSinceCreated(): string
    {
        return $this->created_at->diffForHumans();
    }
}