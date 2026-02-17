<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'student']);
    }

    public function view(User $user, Course $course): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'student']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher']);
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'teacher'
            && $course->teacher_id === $user->id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->role === 'admin';
    }
}
