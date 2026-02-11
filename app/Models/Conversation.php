<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'course_id'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Helper Methods

    /**
     * Get the last message in conversation
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Check if conversation is between two specific users
     */
    public function isBetween(User $user1, User $user2): bool
    {
        return ($this->student_id === $user1->id && $this->teacher_id === $user2->id) ||
               ($this->student_id === $user2->id && $this->teacher_id === $user1->id);
    }

    /**
     * Get the other participant in conversation
     */
    public function otherParticipant(User $user)
    {
        if ($this->student_id === $user->id) {
            return $this->teacher;
        }

        return $this->student;
    }

    /**
     * Get the number of messages in conversation
     */
    public function messageCount(): int
    {
        return $this->messages()->count();
    }

    /**
     * Check if conversation has any messages
     */
    public function hasMessages(): bool
    {
        return $this->messages()->exists();
    }

    /**
     * Get unread message count for a user
     */
    public function unreadCountFor(User $user): int
    {
        return $this->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $user->id)
            ->count();
    }

    /**
     * Mark all messages as read for a user
     */
    public function markAllAsReadFor(User $user): void
    {
        $this->messages()
            ->where('is_read', false)
            ->where('sender_id', '!=', $user->id)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    /**
     * Get time since last message
     */
    public function timeSinceLastMessage(): ?string
    {
        $lastMessage = $this->lastMessage();
        return $lastMessage ? $lastMessage->created_at->diffForHumans() : null;
    }

}
