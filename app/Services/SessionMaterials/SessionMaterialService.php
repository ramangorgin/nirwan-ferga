<?php

namespace App\Services\SessionMaterials;

use App\Models\SessionMaterial;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SessionMaterialService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    public function store(array $data, ?UploadedFile $file, int $actorUserId): SessionMaterial
    {
        return DB::transaction(function () use ($data, $file, $actorUserId) {
            if ($file) {
                $data['file_path'] = $this->storeFile($file, (int) $data['session_id']);
                $data['file_type'] = $data['file_type'] ?? $this->inferFileType($file);
            }

            $data['uploaded_by'] = $data['uploaded_by'] ?? $actorUserId;

            $material = SessionMaterial::create($data);

            $this->notifyMaterialChange($material, $actorUserId, 'created');

            return $material;
        });
    }

    public function update(SessionMaterial $material, array $data, ?UploadedFile $file, int $actorUserId): SessionMaterial
    {
        return DB::transaction(function () use ($material, $data, $file, $actorUserId) {
            $oldPath = $material->file_path;

            $material->fill($data);

            if ($file) {
                $material->file_path = $this->storeFile($file, (int) $material->session_id);
                $material->file_type = $material->file_type ?? $this->inferFileType($file);
            }

            $material->save();

            // Delete old file only AFTER successful save
            if ($file && $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $this->notifyMaterialChange($material->fresh(), $actorUserId, 'updated');

            return $material->fresh();
        });
    }

    public function delete(SessionMaterial $material, int $actorUserId): void
    {
        DB::transaction(function () use ($material, $actorUserId) {
            $path = $material->file_path;

            $this->notifyMaterialChange($material, $actorUserId, 'deleted');

            $material->delete();

            if ($path) {
                Storage::disk('public')->delete($path);
            }
        });
    }

    protected function storeFile(UploadedFile $file, int $sessionId): string
    {
        $dir = "session-materials/{$sessionId}";
        return $file->store($dir, 'public');
    }

    protected function inferFileType(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());

        return match ($ext) {
            'mp4', 'mkv', 'mov', 'avi', 'webm' => 'video',
            'mp3', 'wav', 'ogg', 'm4a' => 'audio',
            'pdf' => 'pdf',
            'ppt', 'pptx', 'key' => 'slides',
            'jpg', 'jpeg', 'png', 'webp', 'gif', 'svg' => 'image',
            default => 'other',
        };
    }

    protected function notifyMaterialChange(SessionMaterial $material, int $actorUserId, string $action): void
    {
        $session = $material->session;
        if (!$session) {
            return;
        }

        $course = $session->course;
        if (!$course) {
            return;
        }

        $actionText = match ($action) {
            'created' => 'اضافه شد',
            'updated' => 'به‌روزرسانی شد',
            'deleted' => 'حذف شد',
            default => 'تغییر کرد',
        };

        $materialTitle = $material->title ?: 'فایل آموزشی';

        // Enrolled students (confirmed/completed)
        $studentIds = $course->enrollments()
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('student_id')
            ->toArray();

        // Notify teacher
        if ($course->teacher_id) {
            $this->notificationService->notifyUser(
                recipientUserId: $course->teacher_id,
                creatorUserId: $actorUserId,
                title: 'مواد آموزشی جلسه تغییر کرد',
                body: "«{$materialTitle}» برای جلسه «{$session->title}» {$actionText}.",
                link: route('class-sessions.show', $session)
            );

            $this->smsService->sendToUserId(
                $course->teacher_id,
                "«{$materialTitle}» برای جلسه «{$session->title}» {$actionText}."
            );
        }

        // Notify students
        foreach ($studentIds as $studentId) {
            $this->notificationService->notifyUser(
                recipientUserId: $studentId,
                creatorUserId: $actorUserId,
                title: 'مواد آموزشی جدید/به‌روزرسانی شد',
                body: "«{$materialTitle}» برای جلسه «{$session->title}» در دوره «{$course->title}» {$actionText}.",
                link: route('class-sessions.show', $session)
            );

            $this->smsService->sendToUserId(
                $studentId,
                "«{$materialTitle}» برای جلسه «{$session->title}» {$actionText}."
            );
        }
    }
}
