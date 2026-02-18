<?php

namespace App\Services\Submissions;

use App\Models\Assignment;
use App\Models\AssignmentPersonalization;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Services\Notifications\NotificationService;
use App\Services\Sms\SmsService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SubmissionService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected SmsService $smsService
    ) {}

    /**
     * ارسال پاسخ توسط دانشجو
     */
    public function submit(Assignment $assignment, int $studentId, array $data, ?UploadedFile $file): Submission
    {
        return DB::transaction(function () use ($assignment, $studentId, $data, $file) {
            $session = $assignment->session;
            $course  = $session?->course;

            if (!$session || !$course) {
                throw ValidationException::withMessages([
                    'assignment' => ['جلسه یا دوره مرتبط با تکلیف یافت نشد.'],
                ]);
            }

            // Enrollment معتبر
            $enrollment = Enrollment::query()
                ->where('student_id', $studentId)
                ->where('course_id', $course->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->first();

            if (!$enrollment) {
                throw ValidationException::withMessages([
                    'assignment' => ['شما در این دوره ثبت‌نام معتبر ندارید.'],
                ]);
            }

            // Attempt limit
            $attemptLimit = (int) config('assignments.attempt_limit', 3);

            $lastAttempt = (int) Submission::query()
                ->where('assignment_id', $assignment->id)
                ->where('student_id', $studentId)
                ->max('attempt_number');

            if ($lastAttempt >= $attemptLimit) {
                throw ValidationException::withMessages([
                    'attempt' => ["شما فقط {$attemptLimit} بار مجاز به ارسال پاسخ برای این تکلیف هستید."],
                ]);
            }

            $attemptNumber = $lastAttempt + 1;

            // تکلیف مؤثر برای این دانشجو (با personalization)
            $effective = $this->effectiveAssignmentForStudent($assignment, $studentId);

            // حداقل یک پاسخ
            $hasAnswer =
                ($file !== null) ||
                (!empty($data['answer_text'])) ||
                (!empty($data['answer_json']));

            if (!$hasAnswer) {
                throw ValidationException::withMessages([
                    'answer' => ['حداقل باید یکی از پاسخ متنی، پاسخ ساختاریافته یا فایل ارسال شود.'],
                ]);
            }

            // deadline / late
            $now = now();
            $deadline = $effective['deadline'];
            $isLate = $deadline ? $now->greaterThan($deadline) : false;

            if ($isLate && !$effective['allow_late']) {
                throw ValidationException::withMessages([
                    'deadline' => ['مهلت ارسال این تکلیف به پایان رسیده است.'],
                ]);
            }

            // ذخیره فایل اگر وجود داشت
            $filePath = null;
            if ($file) {
                $dir = "submissions/{$assignment->id}/{$studentId}";
                $filePath = $file->store($dir, 'public');
            }

            $submission = Submission::create([
                'assignment_id' => $assignment->id,
                'student_id' => $studentId,
                'enrollment_id' => $enrollment->id,

                'attempt_number' => $attemptNumber,
                'status' => $isLate ? 'late_submitted' : 'submitted',
                'submitted_at' => $now,

                'auto_graded' => false,
                'score_obtained' => null,
                'max_score_cached' => (int) ($effective['score'] ?? 1),
                'is_late' => $isLate,

                'answer_text' => $data['answer_text'] ?? null,
                'answer_json' => $data['answer_json'] ?? null,
                'file_path' => $filePath,

                'feedback_text' => null,
            ]);

            // auto-grade اگر لازم باشد
            $this->maybeAutoGrade($submission, $effective);

            // اطلاع‌رسانی به استاد
            if ($course->teacher_id) {
                $this->notificationService->notifyUser(
                    recipientUserId: (int) $course->teacher_id,
                    creatorUserId: $studentId,
                    title: 'پاسخ تکلیف جدید ثبت شد',
                    body: "یک پاسخ جدید برای تکلیف «{$assignment->title}» ثبت شد.",
                    link: route('class-sessions.show', $session)
                );

                $this->smsService->sendToUserId(
                    (int) $course->teacher_id,
                    "پاسخ جدید برای تکلیف «{$assignment->title}» ثبت شد."
                );
            }

            return $submission->fresh();
        });
    }

    /**
     * نمره‌دهی دستی توسط استاد/ادمین
     */
    public function gradeManually(Submission $submission, int $graderId, int $scoreObtained, ?string $feedback): Submission
    {
        return DB::transaction(function () use ($submission, $graderId, $scoreObtained, $feedback) {
            $max = (int) ($submission->max_score_cached ?? 0);

            if ($scoreObtained > $max) {
                throw ValidationException::withMessages([
                    'score_obtained' => ["نمره نمی‌تواند بیشتر از {$max} باشد."],
                ]);
            }

            $submission->update([
                'score_obtained' => $scoreObtained,
                'feedback_text' => $feedback,
                'graded_by' => $graderId,
                'graded_at' => now(),
                'auto_graded' => false,
                'status' => 'graded',
            ]);

            // اطلاع‌رسانی به دانشجو
            $this->notificationService->notifyUser(
                recipientUserId: (int) $submission->student_id,
                creatorUserId: $graderId,
                title: 'تکلیف شما تصحیح شد',
                body: 'نمره و بازخورد تکلیف شما ثبت شد.',
                link: route('class-sessions.show', $submission->assignment->session_id)
            );

            $this->smsService->sendToUserId(
                (int) $submission->student_id,
                'تکلیف شما تصحیح شد و نمره ثبت گردید.'
            );

            return $submission->fresh();
        });
    }

    /**
     * دیتای تکلیف مؤثر برای دانشجو (با personalization)
     */
    protected function effectiveAssignmentForStudent(Assignment $assignment, int $studentId): array
    {
        $p = AssignmentPersonalization::query()
            ->where('assignment_id', $assignment->id)
            ->where('student_id', $studentId)
            ->first();

        return [
            'type' => $p?->custom_type ?? $assignment->type,
            'correct_answer' => $p?->custom_correct_answer ?? $assignment->correct_answer,
            'options' => $p?->custom_options ?? $assignment->options,
            'deadline' => $p?->custom_deadline ?? $assignment->deadline,
            'score' => $p?->custom_score ?? $assignment->score,
            'allow_late' => $assignment->allow_late,
        ];
    }

    /**
     * auto-grade (پشتیبانی از چند پاسخ صحیح)
     */
    protected function maybeAutoGrade(Submission $submission, array $effective): void
    {
        $type = $effective['type'];
        $correct = $effective['correct_answer'];

        // این‌ها دستی هستند
        if (in_array($type, ['translation', 'file'], true)) {
            return;
        }

        // اگر correct_answer نداریم، اتوگرید نکن
        if ($correct === null || trim((string) $correct) === '') {
            return;
        }

        $max = (int) ($effective['score'] ?? 1);

        $studentAnswer = $this->extractStudentAnswer($submission, $type);

        $correctAnswers = $this->parseCorrectAnswers((string) $correct);
        $isCorrect = $this->matchesAnyCorrectAnswer($studentAnswer, $correctAnswers);

        $submission->update([
            'auto_graded' => true,
            'score_obtained' => $isCorrect ? $max : 0,
            'graded_at' => now(),
            'graded_by' => null,
            'status' => 'graded',
        ]);
    }

    /**
     * استخراج پاسخ دانشجو (ساده و قابل ارتقا)
     */
    protected function extractStudentAnswer(Submission $submission, string $type): string
    {
        if ($type === 'mcq') {
            $json = $submission->answer_json;
            if (is_array($json) && array_key_exists('selected', $json)) {
                return (string) $json['selected'];
            }
        }

        return (string) ($submission->answer_text ?? '');
    }

    /**
     * تبدیل correct_answer به آرایه‌ای از پاسخ‌های صحیح
     * جداکننده‌ها: newline، ||، ,، ;، ؛
     */
    protected function parseCorrectAnswers(string $correct): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $correct);

        // || را هم مثل جداکننده در نظر بگیر
        $normalized = str_replace('||', "\n", $normalized);

        // سایر جداکننده‌ها
        $normalized = str_replace([',', ';', '؛'], "\n", $normalized);

        $parts = array_map('trim', explode("\n", $normalized));

        // حذف خالی‌ها
        $parts = array_values(array_filter($parts, fn ($x) => $x !== ''));

        return $parts;
    }

    /**
     * مقایسه با همه پاسخ‌های صحیح (پشتیبانی از کردی/فارسی/عربی)
     */
    protected function matchesAnyCorrectAnswer(string $student, array $correctAnswers): bool
    {
        $studentN = $this->normalizeText($student);

        if ($studentN === '') return false;

        foreach ($correctAnswers as $c) {
            $cN = $this->normalizeText($c);
            if ($cN !== '' && $studentN === $cN) {
                return true;
            }
        }

        return false;
    }

    /**
     * نرمال‌سازی متن برای مقایسه بهتر در زبان‌های کردی/فارسی
     */
    protected function normalizeText(string $text): string
    {
        $t = trim($text);

        // یکسان‌سازی برخی حروف عربی/فارسی
        $t = str_replace(['ي', 'ى', 'ك'], ['ی', 'ی', 'ک'], $t);

        // چند فاصله پشت‌سرهم → یک فاصله
        $t = preg_replace('/\s+/u', ' ', $t) ?? $t;

        return mb_strtolower($t);
    }

    public function deleteFileIfExists(Submission $submission): void
    {
        if ($submission->file_path) {
            Storage::disk('public')->delete($submission->file_path);
        }
    }

    /**
     * برای صفحه استاد: همه submissionهای یک جلسه
     */
    public function listSessionSubmissions(ClassSession $session): array
    {
        $session->load([
            'course',
            'assignments.submissions.student',
        ]);

        // یک خروجی آماده برای Blade:
        // [ assignment => submissions[] ]
        $result = [];

        foreach ($session->assignments as $assignment) {
            $result[$assignment->id] = $assignment->submissions()
                ->with('student')
                ->orderByDesc('submitted_at')
                ->get();
        }

        return $result;
    }
}
