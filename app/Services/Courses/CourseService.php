<?php

namespace App\Services\Courses;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Services\Notifications\NotificationService;


class CourseService
{

    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function create(array $data, int $creatorUserId): Course
    {
        return DB::transaction(function () use ($data, $creatorUserId) {

            $course = Course::create($data);

            if ($course->teacher_id) {
                $this->notificationService->notifyUser(
                    recipientUserId: $course->teacher_id,
                    creatorUserId: $creatorUserId,
                    title: 'دوره جدید به شما اختصاص داده شد',
                    body: "دوره «{$course->title}» ایجاد شد.",
                    link: route('courses.show', $course)
                );
            }

            return $course;
        });
    }

    public function update(Course $course, array $data, int $actorUserId): Course
    {
        return DB::transaction(function () use ($course, $data, $actorUserId) {

            $course->update($data);

            if ($course->teacher_id) {
                $this->notificationService->notifyUser(
                    recipientUserId: $course->teacher_id,
                    creatorUserId: $actorUserId,
                    title: 'دوره شما به‌روز شد',
                    body: "دوره «{$course->title}» به‌روز شد.",
                    link: route('courses.show', $course)
                );
            }

            return $course;
        });
    }
}