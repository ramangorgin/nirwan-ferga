<?php

namespace App\Services\Announcements;

use App\Models\Announcement;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Support\Facades\DB;

class AnnouncementService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService,
    ) {}

    /**
     * Create announcement + sync courses + notify targets.
     */
    public function create(array $data, int $authorId): Announcement
    {
        return DB::transaction(function () use ($data, $authorId) {

            $isPublic = (bool) ($data['is_public'] ?? false);

            $announcement = Announcement::create([
                'author_id' => $authorId,
                'title' => $data['title'],
                'body' => $data['body'],
                'is_public' => $isPublic,
            ]);

            // If course-specific, attach courses
            if (!$isPublic) {
                $courseIds = $data['course_ids'] ?? [];
                $announcement->courses()->sync($courseIds);
            } else {
                $announcement->courses()->sync([]); // ensure no leftover
            }

            // Notify + SMS
            $this->notifyTargets($announcement);

            return $announcement->fresh(['author', 'courses']);
        });
    }

    /**
     * Update announcement + sync courses + notify targets again (simple).
     * Production-fast: Always re-notify after update.
     * If you later want: notify only if changed.
     */
    public function update(Announcement $announcement, array $data): Announcement
    {
        return DB::transaction(function () use ($announcement, $data) {

            if (array_key_exists('title', $data)) $announcement->title = $data['title'];
            if (array_key_exists('body', $data)) $announcement->body = $data['body'];
            if (array_key_exists('is_public', $data)) $announcement->is_public = (bool) $data['is_public'];

            $announcement->save();

            // Sync courses
            if ($announcement->is_public) {
                $announcement->courses()->sync([]);
            } else {
                // If update form sends course_ids, sync them. If not sent, keep existing.
                if (array_key_exists('course_ids', $data)) {
                    $announcement->courses()->sync($data['course_ids'] ?? []);
                }
            }

            // Notify targets again (production-fast)
            $this->notifyTargets($announcement->fresh('courses'));

            return $announcement->fresh(['author', 'courses']);
        });
    }

    public function delete(Announcement $announcement): void
    {
        DB::transaction(function () use ($announcement) {
            $announcement->delete();
        });
    }

    /**
     * Notify targets based on is_public.
     * - Public: notify all users (chunked).
     * - Course specific: notify enrolled students of those courses.
     */
    protected function notifyTargets(Announcement $announcement): void
    {
        $link = route('announcements.public.show', $announcement);

        if ($announcement->is_public) {
            // Notify all registered users (excluding guests obviously)
            User::query()
                ->select('id')
                ->whereIn('role', ['student', 'teacher', 'admin'])
                ->chunkById(500, function ($users) use ($announcement, $link) {
                    foreach ($users as $u) {
                        $this->notificationService->notifyUser(
                            recipientUserId: (int) $u->id,
                            creatorUserId: (int) $announcement->author_id,
                            title: 'اعلان جدید',
                            body: mb_substr($announcement->title, 0, 100),
                            link: $link
                        );

                        // SMS optional: usually expensive; keep it only for high importance later.
                        // For now: send SMS only to students (production-fast compromise)
                        // If you want SMS for all roles, remove the role filter above and send here.
                        $this->smsService->sendToUserId((int) $u->id, "اعلان جدید: {$announcement->title}");
                    }
                });

            return;
        }

        // Course-specific: notify enrolled students in those courses
        $courseIds = $announcement->courses()->pluck('courses.id')->toArray();
        if (empty($courseIds)) return;

        $studentIds = Enrollment::query()
            ->whereIn('course_id', $courseIds)
            ->whereIn('status', ['confirmed', 'completed'])
            ->distinct()
            ->pluck('student_id')
            ->toArray();

        foreach ($studentIds as $studentId) {
            $this->notificationService->notifyUser(
                recipientUserId: (int) $studentId,
                creatorUserId: (int) $announcement->author_id,
                title: 'اعلان مخصوص دوره',
                body: mb_substr($announcement->title, 0, 100),
                link: $link
            );

            $this->smsService->sendToUserId((int) $studentId, "اعلان دوره: {$announcement->title}");
        }
    }
}