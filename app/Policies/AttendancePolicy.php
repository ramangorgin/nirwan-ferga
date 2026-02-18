<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\User;

class AttendancePolicy
{

    public function upsert(User $user, ClassSession $session): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'teacher') {
            return (int) $session->course?->teacher_id === (int) $user->id;
        }

        return false;
    }
}
