<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(?User $user): bool
    {
        // Public announcements can be listed without auth in public controller.
        // This policy is mainly for admin panel; still allow authenticated users.
        return true;
    }

    public function view(?User $user, Announcement $announcement): bool
    {
        // Public is visible for everyone
        if ($announcement->is_public) return true;

        // Course-specific:
        // - Admin can view
        // - Teacher can view (optional)
        // - Student can view only if enrolled in one of its courses (enforced in service/controller)
        if ($user?->role === 'admin') return true;
        if ($user?->role === 'teacher') return true;

        return $user !== null; // student check will be stricter in student controller query
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher'], true);
    }

    public function update(User $user, Announcement $announcement): bool
    {
        if ($user->role === 'admin') return true;

        // Teacher can only edit their own announcements
        if ($user->role === 'teacher') {
            return (int) $announcement->author_id === (int) $user->id;
        }

        return false;
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $this->update($user, $announcement);
    }
}