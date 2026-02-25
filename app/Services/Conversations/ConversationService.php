<?php

namespace App\Services\Conversations;

use App\Models\Conversation;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConversationService
{
    /**
     * Get conversations list for current user.
     * - Admin sees all.
     * - Teacher sees where teacher_id = user id
     * - Student sees where student_id = user id
     */
    public function listForUser(User $user)
    {
        $q = Conversation::query()
            ->with([
                'student',
                'teacher',
                'course',
                // last message (loaded later efficiently)
            ])
            ->latest();

        if ($user->role === 'teacher') {
            $q->where('teacher_id', $user->id);
        } elseif ($user->role === 'student') {
            $q->where('student_id', $user->id);
        }

        return $q->paginate(20);
    }

    /**
     * Find existing conversation between student/teacher/course,
     * or create new one.
     */
    public function findOrCreate(int $studentId, int $teacherId, ?int $courseId = null): Conversation
    {
        return DB::transaction(function () use ($studentId, $teacherId, $courseId) {

            // Basic validation: teacher must be a teacher/admin user
            $teacher = User::query()->findOrFail($teacherId);
            if (!in_array($teacher->role, ['teacher', 'admin'], true)) {
                throw ValidationException::withMessages([
                    'teacher_id' => ['Selected user is not a teacher.'],
                ]);
            }

            // If course is provided: validate student enrolled & teacher owns course (if teacher)
            if ($courseId) {
                $course = Course::query()->findOrFail($courseId);

                $enrolled = Enrollment::query()
                    ->where('student_id', $studentId)
                    ->where('course_id', $courseId)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->exists();

                if (!$enrolled) {
                    throw ValidationException::withMessages([
                        'course_id' => ['Student is not enrolled in this course.'],
                    ]);
                }

                // If teacher is a normal teacher, they must own the course
                if ($teacher->role === 'teacher' && (int) $course->teacher_id !== (int) $teacherId) {
                    throw ValidationException::withMessages([
                        'course_id' => ['Teacher does not belong to this course.'],
                    ]);
                }
            }

            $existing = Conversation::query()
                ->where('student_id', $studentId)
                ->where('teacher_id', $teacherId)
                ->when($courseId, fn ($q) => $q->where('course_id', $courseId), fn ($q) => $q->whereNull('course_id'))
                ->first();

            if ($existing) {
                return $existing;
            }

            return Conversation::create([
                'student_id' => $studentId,
                'teacher_id' => $teacherId,
                'course_id' => $courseId,
            ]);
        });
    }
}