<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher'], true);
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') return true;

        if ($user->role === 'teacher') {
            return (int) $quiz->course?->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher'], true);
    }

    public function update(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return $this->view($user, $quiz);
    }

    // Student access: only enrolled students can take
    public function take(User $user, Quiz $quiz): bool
    {
        return $user->role === 'student';
    }
}
