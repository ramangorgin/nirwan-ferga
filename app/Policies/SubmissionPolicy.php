<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function submit(User $user): bool
    {
        return $user->role === 'student';
    }

    public function grade(User $user, Submission $submission): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $submission->assignment?->session?->course?->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function delete(User $user, Submission $submission): bool
    {
        return $this->grade($user, $submission);
    }
}
