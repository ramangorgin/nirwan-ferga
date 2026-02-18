<?php

namespace App\Policies;

use App\Models\QuizSubmission;
use App\Models\User;

class QuizSubmissionPolicy
{
    public function view(User $user, QuizSubmission $submission): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $submission->quiz?->course?->teacher_id === (int) $user->id;
        }

        if ($user->role === 'student') {
            return (int) $submission->student_id === (int) $user->id;
        }

        return false;
    }

    public function submit(User $user): bool
    {
        return $user->role === 'student';
    }
}
