<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\User;
use App\Models\Course;

class ClassSessionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user, Course $course): bool
    {
        if ($user->role === 'teacher') {
            return $course->teacher_id === $user->id;
        }

        // student
        return $course->enrollments()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();
    }

    public function view(User $user, ClassSession $session): bool
    {
        if ($user->role === 'teacher') {
            return $session->course->teacher_id === $user->id;
        }

        // student
        return $session->course->enrollments()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();
    }

    public function create(User $user, Course $course): bool
    {
        return $user->role === 'teacher' 
            && $course->teacher_id === $user->id;
    }

    public function update(User $user, ClassSession $session): bool
    {
        return $user->role === 'teacher'
            && $session->course->teacher_id === $user->id;
    }

    public function delete(User $user, ClassSession $session): bool
    {
        return $user->role === 'teacher'
            && $session->course->teacher_id === $user->id;
    }
}
