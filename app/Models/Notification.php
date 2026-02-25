<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // creator/author
        'title',
        'body',
        'link',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Creator (author) of notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Recipients (per-user read state stored in pivot).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    // -------------------------
    // Per-user read helpers (pivot-based)
    // -------------------------

    /**
     * Check if this notification is read by the given user (pivot read_at).
     */
    public function isReadBy(User $user): bool
    {
        $pivot = $this->users()
            ->where('users.id', $user->id)
            ->first()?->pivot;

        return $pivot?->read_at !== null;
    }

    public function isUnreadBy(User $user): bool
    {
        return !$this->isReadBy($user);
    }

    /**
     * Mark this notification as read for the given user.
     */
    public function markAsReadFor(User $user): void
    {
        $this->users()->updateExistingPivot($user->id, [
            'read_at' => now('UTC'),
        ]);
    }

    /**
     * Mark this notification as unread for the given user.
     */
    public function markAsUnreadFor(User $user): void
    {
        $this->users()->updateExistingPivot($user->id, [
            'read_at' => null,
        ]);
    }

    public function hasLink(): bool
    {
        return $this->link !== null;
    }

    public function hasBody(): bool
    {
        return $this->body !== null;
    }

    public function timeSinceCreated(): string
    {
        return $this->created_at->diffForHumans();
    }
}