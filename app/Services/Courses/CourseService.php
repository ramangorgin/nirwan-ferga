<?php

namespace App\Services\Courses;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use App\Services\DateTime\DateTimeService;

class CourseService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService,                 // optional but recommended
        protected DateTimeService $dateTimeService        // for Jalali/UTC conversions
    ) {}

    /**
     * Normalize Jalali inputs for Course before saving:
     * - registration_deadline: Jalali datetime -> UTC Carbon
     * - start_date/end_date: Jalali date -> Gregorian Y-m-d
     * - start_time: local time -> UTC time (anchored to start_date)
     */
    protected function normalizeCourseDateTimes(array $data, string $actorTimezone): array
    {
        // Dates (Jalali date -> Gregorian date string)
        if (!empty($data['start_date'])) {
            $data['start_date'] = $this->dateTimeService->jalaliDateToGregorian($data['start_date']);
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = $this->dateTimeService->jalaliDateToGregorian($data['end_date']);
        }

        // Datetime (Jalali datetime -> UTC Carbon)
        if (!empty($data['registration_deadline'])) {
            $data['registration_deadline'] = $this->dateTimeService->jalaliToUtc(
                $data['registration_deadline'],
                $actorTimezone
            );
        }

        /**
         * Time column: store as UTC time string.
         * We must anchor time to a date to convert to UTC correctly.
         * We use start_date as the anchor.
         */
        if (!empty($data['start_time'])) {
            $anchorJalaliDate = null;

            // If start_date is coming from blade, it's Jalali. Use it.
            // If start_date already normalized above, we still need Jalali for parsing.
            // Best practice: always send start_date from blade on create/update.
            if (!empty($data['_jalali_start_date_for_time'])) {
                $anchorJalaliDate = $data['_jalali_start_date_for_time'];
                unset($data['_jalali_start_date_for_time']);
            }

            // If we don't have a Jalali anchor, try using the raw start_date input (common case)
            if ($anchorJalaliDate === null && !empty($data['start_date_jalali_raw'])) {
                $anchorJalaliDate = $data['start_date_jalali_raw'];
                unset($data['start_date_jalali_raw']);
            }

            // If still null: fall back to using "start_date" as if it's Jalali (works if you didn't normalize early)
            $anchorJalaliDate = $anchorJalaliDate ?? ($data['start_date'] ?? null);

            if ($anchorJalaliDate) {
                $utcParts = $this->dateTimeService->jalaliDateAndTimeToUtcParts(
                    $anchorJalaliDate,
                    $data['start_time'],
                    $actorTimezone
                );

                $data['start_time'] = $utcParts['time']; // store UTC time (H:i:s)
            } else {
                // If you ever hit this, you should send start_date with the form.
                // We'll keep the time as-is to avoid breaking production.
                // But this is not timezone-safe.
            }
        }

        return $data;
    }

    /**
     * Create a new course
     */
    public function create(array $data, int $creatorUserId, string $creatorTimezone): Course
    {
        return DB::transaction(function () use ($data, $creatorUserId, $creatorTimezone) {

            $data = $this->normalizeCourseDateTimes($data, $creatorTimezone);

            $course = Course::create($data);

            if ($course->teacher_id) {
                // Notification
                $this->notificationService->notifyUser(
                    recipientUserId: $course->teacher_id,
                    creatorUserId: $creatorUserId,
                    title: 'دوره جدید به شما اختصاص داده شد',
                    body: "دوره «{$course->title}» ایجاد شد.",
                    link: route('courses.show', $course)
                );

                // SMS (optional)
                $this->smsService->sendToUserId(
                    (int) $course->teacher_id,
                    "دوره جدید «{$course->title}» به شما اختصاص داده شد."
                );
            }

            return $course;
        });
    }

    /**
     * Update an existing course
     */
    public function update(Course $course, array $data, int $actorUserId, string $actorTimezone): Course
    {
        return DB::transaction(function () use ($course, $data, $actorUserId, $actorTimezone) {

            $data = $this->normalizeCourseDateTimes($data, $actorTimezone);

            $course->update($data);

            if ($course->teacher_id) {
                // Notification
                $this->notificationService->notifyUser(
                    recipientUserId: $course->teacher_id,
                    creatorUserId: $actorUserId,
                    title: 'دوره شما به‌روز شد',
                    body: "دوره «{$course->title}» به‌روز شد.",
                    link: route('courses.show', $course)
                );

                // SMS (optional)
                $this->smsService->sendToUserId(
                    (int) $course->teacher_id,
                    "دوره «{$course->title}» به‌روز شد."
                );
            }

            return $course->fresh();
        });
    }
}