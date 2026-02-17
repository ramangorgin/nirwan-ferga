<?php

namespace App\Services\Enrollments;

use App\Models\Course;
use App\Models\Enrollment;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    /**
     * Manually enroll a student in a course
     * 
     * @param Course $course Course to enroll in
     * @param int $studentId Student user ID
     * @param int $actorUserId User ID performing the action
     * @param int|null $paidAmount Amount paid (defaults to course price)
     * @return Enrollment
     * @throws ValidationException
     */
    public function manualEnroll(Course $course, int $studentId, int $actorUserId, ?int $paidAmount = null): Enrollment
    {
        return DB::transaction(function () use ($course, $studentId, $actorUserId, $paidAmount) {

            if (! $course->isRegistrationOpen()) {
                throw ValidationException::withMessages(['course_id' => 'ثبت‌نام برای این دوره بسته شده است.']);
            }

            if ($course->isFull()) {
                throw ValidationException::withMessages(['course_id' => 'دوره پر شده است.']);
            }

            $exists = Enrollment::where('course_id', $course->id)
                ->where('student_id', $studentId)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages(['student_id' => 'زبان‌آموز قبلاً در این دوره ثبت‌نام کرده است.']);
            }

            $enrollment = Enrollment::create([
                'course_id' => $course->id,
                'student_id' => $studentId,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_amount' => $paidAmount ?? $course->price,
            ]);

            // notify student (db + sms hook)
            $this->notificationService->notifyUser(
                recipientUserId: $studentId,
                creatorUserId: $actorUserId,
                title: 'ثبت‌نام تایید شد',
                body: "ثبت‌نام شما در دوره «{$course->title}» تایید شد.",
                link: route('courses.show', $course)
            );

            $this->smsService->sendToUserId($studentId, "ثبت‌نام شما در دوره «{$course->title}» تایید شد.");

            return $enrollment;
        });
    }

    /**
     * Verify or update enrollment status and payment
     * 
     * @param Enrollment $enrollment Enrollment to update
     * @param array $data Updated data
     * @param int $actorUserId User ID performing the action
     * @return Enrollment
     * @throws ValidationException
     */
    public function verifyOrUpdate(Enrollment $enrollment, array $data, int $actorUserId): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $data, $actorUserId) {

            // Business rule: cannot confirm unless paid
            $newStatus = $data['status'] ?? $enrollment->status;
            $newPayment = $data['payment_status'] ?? $enrollment->payment_status;

            if ($newStatus === 'confirmed' && $newPayment !== 'paid') {
                throw ValidationException::withMessages([
                    'status' => 'نمی‌توان ثبت‌نام را تایید کرد مگر اینکه وضعیت پرداخت "پرداخت شده" باشد.'
                ]);
            }

            $enrollment->update($data);

            // notify student on important transitions
            if (array_key_exists('status', $data) || array_key_exists('payment_status', $data)) {
                $courseTitle = $enrollment->course?->title ?? 'دوره';

                $title = 'وضعیت ثبت‌نام بروزرسانی شد';
                $body = "وضعیت ثبت‌نام شما برای «{$courseTitle}» بروزرسانی شد.";

                $this->notificationService->notifyUser(
                    recipientUserId: $enrollment->student_id,
                    creatorUserId: $actorUserId,
                    title: $title,
                    body: $body,
                    link: route('courses.show', $enrollment->course_id)
                );

                $this->smsService->sendToUserId($enrollment->student_id, $body);
            }

            return $enrollment;
        });
    }

    /**
     * Cancel an enrollment
     * 
     * @param Enrollment $enrollment Enrollment to cancel
     * @param int $actorUserId User ID performing the action
     * @return Enrollment
     */
    public function cancel(Enrollment $enrollment, int $actorUserId): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $actorUserId) {

            // idempotent-ish
            if ($enrollment->status !== 'cancelled') {
                $enrollment->update(['status' => 'cancelled']);
            }

            $courseTitle = $enrollment->course?->title ?? 'دوره';

            $this->notificationService->notifyUser(
                recipientUserId: $enrollment->student_id,
                creatorUserId: $actorUserId,
                title: 'ثبت‌نام لغو شد',
                body: "ثبت‌نام شما برای «{$courseTitle}» لغو شد.",
                link: route('courses.show', $enrollment->course_id)
            );

            $this->smsService->sendToUserId($enrollment->student_id, "ثبت‌نام شما برای «{$courseTitle}» لغو شد.");

            return $enrollment;
        });
    }
}
