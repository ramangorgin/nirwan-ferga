<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'percentage',
        'max_uses',
        'user_id',
        'expires_at',
        'active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
        'max_uses' => 'integer',
        'percentage' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Helper Methods

    /**
     * Check if discount code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if discount code is available
     */
    public function isAvailable(): bool
    {
        return $this->active && !$this->isExpired();
    }

    /**
     * Check if discount code is restricted to specific user
     */
    public function isRestricted(): bool
    {
        return $this->user_id !== null;
    }

    /**
     * Check if discount code can be used by user
     */
    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->isRestricted() && $this->user_id !== $user->id) {
            return false;
        }

        // Check if max uses limit is reached
        if ($this->max_uses && $this->enrollments()->count() >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Get the number of remaining uses
     */
    public function remainingUses(): ?int
    {
        if (!$this->max_uses) {
            return null;
        }

        $usedCount = $this->enrollments()->count();
        return max(0, $this->max_uses - $usedCount);
    }

    /**
     * Check if discount code has unlimited uses
     */
    public function hasUnlimitedUses(): bool
    {
        return $this->max_uses === null;
    }

}
