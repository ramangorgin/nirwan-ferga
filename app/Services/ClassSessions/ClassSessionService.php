<?php

namespace App\Services\ClassSessions;

use App\Models\ClassSession;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;

class ClassSessionService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    public function updateStatus(ClassSession $classSession, string $status, int $actorUserId): ClassSession
    {
        $classSession->status = $status;
        $classSession->save();

        $this->notifyChange($classSession, $actorUserId);

        return $classSession;
    }

    protected function notifyChange(ClassSession $classSession, int $actorUserId): void
    {
        $course = $classSession->course;
        if (!$course) {
            return;
        }

        // Get all enrolled students
        $enrolledStudents = $course->enrollments()
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('student_id')
            ->toArray();

        // Notify teacher
        if ($course->teacher_id) {
            $statusMessage = match($classSession->status) {
                'held' => 'برگزار شد',
                'cancelled' => 'لغو شد',
                'postponed' => 'به تعویق افتاد',
                default => 'به‌روز شد',
            };

            $this->notificationService->notifyUser(
                recipientUserId: $course->teacher_id,
                creatorUserId: $actorUserId,
                title: 'وضعیت جلسه تغییر کرد',
                body: "جلسه «{$classSession->title}» $statusMessage.",
                link: route('class-sessions.show', $classSession)
            );

            $this->smsService->sendToUserId(
                $course->teacher_id,
                "جلسه «{$classSession->title}» $statusMessage."
            );
        }

        // Notify all enrolled students
        foreach ($enrolledStudents as $studentId) {
            $this->notificationService->notifyUser(
                recipientUserId: $studentId,
                creatorUserId: $actorUserId,
                title: 'وضعیت جلسه تغییر کرد',
                body: "جلسه «{$classSession->title}» برای دوره «{$course->title}» $statusMessage.",
                link: route('class-sessions.show', $classSession)
            );

            $this->smsService->sendToUserId(
                $studentId,
                "جلسه «{$classSession->title}» $statusMessage."
            );
        }
    }
}
