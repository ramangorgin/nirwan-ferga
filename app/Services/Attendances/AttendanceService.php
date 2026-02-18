<?php

namespace App\Services\Attendances;

use App\Models\Attendance;
use App\Models\ClassSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
 
    public function upsertBulk(ClassSession $session, array $attendances): void
    {
        DB::transaction(function () use ($session, $attendances) {

            // دانشجوهای معتبر این دوره (فقط confirmed/completed)
            $course = $session->course;

            $allowedStudentIds = $course
                ->enrollments()
                ->whereIn('status', ['confirmed', 'completed'])
                ->pluck('student_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            // student_idها از کلیدهای آرایه می‌آیند
            $submittedStudentIds = array_map('intval', array_keys($attendances));

            // student_idهای نامعتبر نسبت به دوره
            $invalid = array_values(array_diff($submittedStudentIds, $allowedStudentIds));

            if (!empty($invalid)) {
                throw ValidationException::withMessages([
                    'attendances' => [
                        'یک یا چند دانش‌آموز برای این دوره مجاز نیستند: ' . implode(', ', $invalid),
                    ],
                ]);
            }

            // upsert (به خاطر unique(session_id, student_id))
            foreach ($attendances as $studentId => $row) {
                Attendance::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'student_id' => (int) $studentId,
                    ],
                    [
                        'status' => $row['status'],
                        'note' => $row['note'] ?? null,
                    ]
                );
            }
        });
    }
}
