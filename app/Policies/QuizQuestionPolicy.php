<?php

namespace App\Policies;

use App\Models\QuizQuestion;
use App\Models\User;

class QuizQuestionPolicy
{
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher'], true);
    }

    public function update(User $user, QuizQuestion $question): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $question->quiz?->course?->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function delete(User $user, QuizQuestion $question): bool
    {
        return $this->update($user, $question);
    }
}
