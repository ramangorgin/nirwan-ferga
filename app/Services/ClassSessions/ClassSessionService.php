<?php

namespace App\Services\ClassSessions;

use App\Models\ClassSession;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use App\Services\DateTime\DateTimeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClassSessionService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService,
        protected DateTimeService $dateTimeService,
    ) {}

    public function store(array $data, string $actorTimezone): ClassSession
    {
        return DB::transaction(function () use ($data, $actorTimezone) {
            // Convert start
            $start = $this->dateTimeService->jalaliDateAndTimeToUtcParts(
                $data['session_date'],
                $data['start_time'],
                $actorTimezone
            );

            // Convert end (same jalali date initially)
            $end = $this->dateTimeService->jalaliDateAndTimeToUtcParts(
                $data['session_date'],
                $data['end_time'],
                $actorTimezone
            );

            // If end is before start in UTC, assume session crosses midnight (end next day)
            if ($end['carbon']->lessThanOrEqualTo($start['carbon'])) {
                $endCarbon = $end['carbon']->copy()->addDay();
                $end = [
                    'date' => $endCarbon->format('Y-m-d'),
                    'time' => $endCarbon->format('H:i:s'),
                    'carbon' => $endCarbon,
                ];
            }

            // Store UTC date/time parts
            $data['session_date'] = $start['date'];
            $data['start_time']   = $start['time'];
            $data['end_time']     = $end['time'];

            return ClassSession::create($data);
        });
    }

    public function update(ClassSession $classSession, array $data, string $actorTimezone): ClassSession
    {
        return DB::transaction(function () use ($classSession, $data, $actorTimezone) {
            // Only convert when the fields exist
            $jalaliDate = $data['session_date'] ?? $classSession->session_date->format('Y-m-d'); 
            // NOTE: if current DB is UTC date, this fallback is OK for update if form sends session_date always.
            // Best practice: always send session_date in update form.

            if (array_key_exists('session_date', $data) || array_key_exists('start_time', $data)) {
                $startTime = $data['start_time'] ?? $classSession->start_time; // may be string
                $start = $this->dateTimeService->jalaliDateAndTimeToUtcParts($jalaliDate, $startTime, $actorTimezone);

                $data['session_date'] = $start['date'];
                $data['start_time'] = $start['time'];
            }

            if (array_key_exists('session_date', $data) || array_key_exists('end_time', $data) || array_key_exists('start_time', $data)) {
                $endTime = $data['end_time'] ?? $classSession->end_time;
                $startUtc = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', ($data['session_date'] ?? $classSession->session_date->format('Y-m-d')) . ' ' . ($data['start_time'] ?? $classSession->start_time), 'UTC');

                $end = $this->dateTimeService->jalaliDateAndTimeToUtcParts($jalaliDate, $endTime, $actorTimezone);

                if ($end['carbon']->lessThanOrEqualTo($startUtc)) {
                    $endCarbon = $end['carbon']->copy()->addDay();
                    $data['end_time'] = $endCarbon->format('H:i:s');
                } else {
                    $data['end_time'] = $end['time'];
                }
            }

            $classSession->update($data);

            return $classSession->fresh();
        });
    }

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
