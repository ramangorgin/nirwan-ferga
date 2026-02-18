<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher'], true);
    }

    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $assignment->session?->course?->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->update($user, $assignment);
    }

    public function personalize(User $user, Assignment $assignment): bool
    {
        return $this->update($user, $assignment);
    }
}
