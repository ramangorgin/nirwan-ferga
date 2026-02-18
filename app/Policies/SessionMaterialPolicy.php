<?php

namespace App\Policies;

use App\Models\ClassSession;
use App\Models\SessionMaterial;
use App\Models\User;

class SessionMaterialPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user, ClassSession $session): bool
    {
        if ($user->role === 'teacher') {
            return $session->course->teacher_id === $user->id;
        }

        // students: can list materials only if enrolled (confirmed/completed)
        return $user->role === 'student'
            && $session->course->enrollments()
                ->where('student_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->exists();
    }

    public function view(User $user, SessionMaterial $material): bool
    {
        if ($user->role === 'teacher') {
            return $material->session->course->teacher_id === $user->id;
        }

        if ($user->role !== 'student') {
            return false;
        }

        // visibility rules
        if ($material->visibility === 'hidden') {
            return false;
        }

        if ($material->visibility === 'public') {
            return true;
        }

        // students_only
        return $material->session->course->enrollments()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();
    }

    public function create(User $user, ClassSession $session): bool
    {
        return $user->role === 'teacher'
            && $session->course->teacher_id === $user->id;
    }

    public function update(User $user, SessionMaterial $material): bool
    {
        return $user->role === 'teacher'
            && $material->session->course->teacher_id === $user->id;
    }

    public function delete(User $user, SessionMaterial $material): bool
    {
        return $user->role === 'teacher'
            && $material->session->course->teacher_id === $user->id;
    }
}
