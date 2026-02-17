<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['teacher', 'student'], true);
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'teacher') {
            return $enrollment->course->teacher_id === $user->id;
        }

        // student
        return $enrollment->student_id === $user->id;
    }

    public function create(User $user): bool
    {
        // student self-enroll allowed; teacher allowed.
        return in_array($user->role, ['teacher', 'student'], true);
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        // teachers can update enrollments only for their courses
        return $user->role === 'teacher'
            && $enrollment->course->teacher_id === $user->id;
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        // same scope as update
        return $user->role === 'teacher'
            && $enrollment->course->teacher_id === $user->id;
    }

    public function cancel(User $user, Enrollment $enrollment): bool
    {
        if ($user->role === 'teacher') {
            return $enrollment->course->teacher_id === $user->id;
        }

        // student can cancel only their own pending enrollment
        return $user->role === 'student'
            && $enrollment->student_id === $user->id
            && $enrollment->status === 'pending';
    }

}
