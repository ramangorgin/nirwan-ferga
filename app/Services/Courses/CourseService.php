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

    /**
     * Create a new course
     * 
     * @param array $data Course data
     * @param int $creatorUserId User ID creating the course
     * @return Course
     */
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

    /**
     * Update an existing course
     * 
     * @param Course $course Course to update
     * @param array $data Updated data
     * @param int $actorUserId User ID performing the update
     * @return Course
     */
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
