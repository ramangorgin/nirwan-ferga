<?php

namespace App\Services\Assignments;

use App\Models\Assignment;
use App\Models\AssignmentPersonalization;
use App\Models\ClassSession;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AssignmentService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    public function store(array $data, int $actorUserId): Assignment
    {
        return DB::transaction(function () use ($data, $actorUserId) {
            $assignment = Assignment::create([
                'session_id' => $data['session_id'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'correct_answer' => $data['correct_answer'] ?? null,
                'options' => $data['options'] ?? null,
                'score' => $data['score'] ?? 1,
                'deadline' => $data['deadline'],
                'allow_late' => $data['allow_late'] ?? false,
                'status' => $data['status'] ?? 'draft',
            ]);

            if ($assignment->status === 'published') {
                $this->notifyPublished($assignment, $actorUserId);
            }

            return $assignment;
        });
    }

    public function update(Assignment $assignment, array $data, int $actorUserId): Assignment
    {
        return DB::transaction(function () use ($assignment, $data, $actorUserId) {
            $wasPublished = $assignment->status === 'published';

            $assignment->fill($data);
            $assignment->save();

            // اگر تازه published شد، نوتیفیکیشن بده
            $isPublishedNow = $assignment->status === 'published';
            if (!$wasPublished && $isPublishedNow) {
                $this->notifyPublished($assignment, $actorUserId);
            }

            return $assignment->fresh();
        });
    }

    public function delete(Assignment $assignment): void
    {
        DB::transaction(function () use ($assignment) {
            // با restrictOnDelete، اگر submission داشته باشه حذف نمی‌شه و خطا می‌ده
            $assignment->delete();
        });
    }

    /**
     * personalized bulk upsert
     * @param array $personalizations  کلیدها student_id هستند
     */
    public function upsertPersonalizations(Assignment $assignment, array $personalizations, int $actorUserId): void
    {
        DB::transaction(function () use ($assignment, $personalizations, $actorUserId) {

            $session = $assignment->session;
            if (!$session) {
                throw ValidationException::withMessages([
                    'personalizations' => ['جلسه‌ی مرتبط با تکلیف یافت نشد.'],
                ]);
            }

            $course = $session->course;
            if (!$course) {
                throw ValidationException::withMessages([
                    'personalizations' => ['دوره‌ی مرتبط با جلسه یافت نشد.'],
                ]);
            }

            // فقط دانشجوهای ثبت‌نام confirmed/completed مجازند
            $allowedStudentIds = $course->enrollments()
                ->whereIn('status', ['confirmed', 'completed'])
                ->pluck('student_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();

            $submittedStudentIds = array_map('intval', array_keys($personalizations));
            $invalid = array_values(array_diff($submittedStudentIds, $allowedStudentIds));

            if (!empty($invalid)) {
                throw ValidationException::withMessages([
                    'personalizations' => ['یک یا چند دانش‌آموز برای این دوره مجاز نیستند: ' . implode(', ', $invalid)],
                ]);
            }

            foreach ($personalizations as $studentId => $row) {
                AssignmentPersonalization::updateOrCreate(
                    [
                        'assignment_id' => $assignment->id,
                        'student_id' => (int) $studentId,
                    ],
                    [
                        'custom_title' => $row['custom_title'] ?? null,
                        'custom_description' => $row['custom_description'] ?? null,
                        'custom_type' => $row['custom_type'] ?? null,
                        'custom_options' => $row['custom_options'] ?? null,
                        'custom_correct_answer' => $row['custom_correct_answer'] ?? null,
                        'custom_deadline' => $row['custom_deadline'] ?? null,
                        'custom_score' => $row['custom_score'] ?? null,
                        'created_by' => $actorUserId,
                    ]
                );
            }
        });
    }

    protected function notifyPublished(Assignment $assignment, int $actorUserId): void
    {
        $session = $assignment->session;
        $course = $session?->course;

        if (!$session || !$course) return;

        $studentIds = $course->enrollments()
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('student_id')
            ->toArray();

        foreach ($studentIds as $studentId) {
            $this->notificationService->notifyUser(
                recipientUserId: $studentId,
                creatorUserId: $actorUserId,
                title: 'تکلیف جدید منتشر شد',
                body: "تکلیف «{$assignment->title}» برای جلسه «{$session->title}» منتشر شد.",
                link: route('class-sessions.show', $session)
            );

            $this->smsService->sendToUserId(
                (int) $studentId,
                "تکلیف «{$assignment->title}» برای جلسه «{$session->title}» منتشر شد."
            );
        }
    }
}
