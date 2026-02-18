<?php

namespace App\Services\Quizzes;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\QuizSubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuizSubmissionService
{
    /**
     * Get "now" in UTC for consistent comparisons (quiz times stored in UTC).
     */
    protected function nowUtc(): Carbon
    {
        return now('UTC');
    }

    /**
     * Ensure the student is enrolled and quiz is available.
     */
    protected function ensureStudentCanAccessQuiz(Quiz $quiz, int $studentId): void
    {
        $enrollment = Enrollment::query()
            ->where('student_id', $studentId)
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();

        if (!$enrollment) {
            throw ValidationException::withMessages([
                'quiz' => ['شما در این دوره ثبت‌نام معتبر ندارید.'],
            ]);
        }

        if ($quiz->visibility !== 'published') {
            throw ValidationException::withMessages([
                'quiz' => ['این آزمون در حال حاضر قابل دسترسی نیست.'],
            ]);
        }

        $now = $this->nowUtc();

        if ($now->lt($quiz->start_at) || $now->gt($quiz->end_at)) {
            throw ValidationException::withMessages([
                'quiz' => ['زمان آزمون فعال نیست.'],
            ]);
        }
    }

    /**
     * Create or reuse a pending submission for current attempt when student opens the quiz.
     */
    public function startOrGetPendingSubmission(Quiz $quiz, int $studentId): QuizSubmission
    {
        return DB::transaction(function () use ($quiz, $studentId) {
            $this->ensureStudentCanAccessQuiz($quiz, $studentId);

            // If there is a pending submission, reuse it
            $pending = QuizSubmission::query()
                ->where('quiz_id', $quiz->id)
                ->where('student_id', $studentId)
                ->whereNull('finished_at')
                ->orderByDesc('id')
                ->first();

            if ($pending) {
                // If time limit exceeded, force-finish with current answers (or zero)
                if ($this->isTimeExceeded($quiz, $pending)) {
                    $this->forceFinish($quiz, $pending);
                } else {
                    return $pending;
                }
            }

            // Attempt limit
            $attemptCount = QuizSubmission::query()
                ->where('quiz_id', $quiz->id)
                ->where('student_id', $studentId)
                ->count();

            if ($attemptCount >= (int) $quiz->attempt_limit) {
                throw ValidationException::withMessages([
                    'attempt' => ["شما فقط {$quiz->attempt_limit} بار مجاز به شرکت در این آزمون هستید."],
                ]);
            }

            $attemptNumber = $attemptCount + 1;

            return QuizSubmission::create([
                'student_id' => $studentId,
                'quiz_id' => $quiz->id,
                'attempt_number' => $attemptNumber,
                'started_at' => $this->nowUtc(),
                'finished_at' => null,
                'total_score' => null,
                'passed' => null,
            ]);
        });
    }

    /**
     * Submit answers and finish the quiz.
     * Auto-grades if quiz->auto_grade is true.
     */
    public function submit(Quiz $quiz, QuizSubmission $submission, int $studentId, array $answers): QuizSubmission
    {
        return DB::transaction(function () use ($quiz, $submission, $studentId, $answers) {
            $this->ensureStudentCanAccessQuiz($quiz, $studentId);

            if ((int) $submission->student_id !== (int) $studentId || (int) $submission->quiz_id !== (int) $quiz->id) {
                throw ValidationException::withMessages([
                    'submission' => ['ارسال آزمون نامعتبر است.'],
                ]);
            }

            if ($submission->finished_at !== null) {
                throw ValidationException::withMessages([
                    'submission' => ['این آزمون قبلاً ثبت نهایی شده است.'],
                ]);
            }

            if ($this->isTimeExceeded($quiz, $submission)) {
                // If exceeded, force finish first, then refuse new submission
                $this->forceFinish($quiz, $submission);
                throw ValidationException::withMessages([
                    'time' => ['زمان آزمون شما به پایان رسیده است.'],
                ]);
            }

            $questions = $quiz->questions()->orderBy('order_index')->get();
            if ($questions->count() === 0) {
                throw ValidationException::withMessages([
                    'quiz' => ['این آزمون سوالی ندارد.'],
                ]);
            }

            // Save answers (one per question)
            foreach ($questions as $q) {
                $studentAnswer = $answers[$q->id] ?? null;

                QuizAnswer::updateOrCreate(
                    [
                        'submission_id' => $submission->id,
                        'question_id' => $q->id,
                    ],
                    [
                        'student_answer' => $studentAnswer,
                        'score_awarded' => null,
                    ]
                );
            }

            // Auto grade if enabled
            if ($quiz->auto_grade) {
                $this->autoGradeSubmission($quiz, $submission);
            }

            // Finish
            $submission->update([
                'finished_at' => $this->nowUtc(),
            ]);

            // Determine pass/fail if passing_score exists and total_score is computed
            $submission->refresh();

            if ($quiz->passing_score !== null && $submission->total_score !== null) {
                $submission->update([
                    'passed' => $submission->total_score >= (int) $quiz->passing_score,
                ]);
            }

            return $submission->fresh(['answers.question', 'quiz']);
        });
    }

    /**
     * Auto grade all answers for a submission.
     * Supports multiple correct answers, Kurdish-friendly normalization.
     */
    public function autoGradeSubmission(Quiz $quiz, QuizSubmission $submission): void
    {
        $answers = $submission->answers()->with('question')->get();
        $total = 0;

        foreach ($answers as $ans) {
            $q = $ans->question;
            if (!$q) continue;

            $score = $this->gradeOne($q, (string) ($ans->student_answer ?? ''));
            $ans->update(['score_awarded' => $score]);

            $total += $score;
        }

        $submission->update(['total_score' => $total]);
    }

    /**
     * Grade a single question.
     * - mcq/true_false: direct compare but normalized for text values
     * - fill_blank/text: compare against any correct answer option
     */
    protected function gradeOne(QuizQuestion $question, string $studentAnswer): int
    {
        $max = (int) ($question->score ?? 1);

        // If no correct answer provided, cannot auto-grade safely
        if ($question->correct_answer === null || trim((string) $question->correct_answer) === '') {
            return 0;
        }

        $correctAnswers = $this->parseCorrectAnswers((string) $question->correct_answer);

        $studentN = $this->normalizeText($studentAnswer);

        if ($studentN === '') {
            return 0;
        }

        foreach ($correctAnswers as $c) {
            $cN = $this->normalizeText($c);
            if ($cN !== '' && $studentN === $cN) {
                return $max;
            }
        }

        return 0;
    }

    /**
     * Correct answer can be multiple lines or separated by || , ; ؛
     */
    protected function parseCorrectAnswers(string $correct): array
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", $correct);
        $normalized = str_replace('||', "\n", $normalized);
        $normalized = str_replace([',', ';', '؛'], "\n", $normalized);

        $parts = array_map('trim', explode("\n", $normalized));
        return array_values(array_filter($parts, fn ($x) => $x !== ''));
    }

    /**
     * Kurdish/Persian/Arabic text normalization for safer comparisons.
     */
    protected function normalizeText(string $text): string
    {
        $t = trim($text);

        // Normalize Arabic chars to Persian equivalents
        $t = str_replace(['ي', 'ى', 'ك'], ['ی', 'ی', 'ک'], $t);

        // Collapse multiple whitespaces
        $t = preg_replace('/\s+/u', ' ', $t) ?? $t;

        return mb_strtolower($t);
    }

    /**
     * Check if student exceeded duration_minutes from started_at.
     */
    protected function isTimeExceeded(Quiz $quiz, QuizSubmission $submission): bool
    {
        if (!$submission->started_at) return false;

        $deadline = $submission->started_at->copy()->addMinutes((int) $quiz->duration_minutes);
        return $this->nowUtc()->gt($deadline);
    }

    /**
     * Force finish an expired attempt.
     * If auto_grade is enabled, grade whatever answers exist.
     */
    protected function forceFinish(Quiz $quiz, QuizSubmission $submission): void
    {
        if ($submission->finished_at !== null) return;

        if ($quiz->auto_grade) {
            $this->autoGradeSubmission($quiz, $submission);
        } else {
            // If no grading, keep total_score null
        }

        $submission->update([
            'finished_at' => $this->nowUtc(),
        ]);

        $submission->refresh();

        if ($quiz->passing_score !== null && $submission->total_score !== null) {
            $submission->update([
                'passed' => $submission->total_score >= (int) $quiz->passing_score,
            ]);
        }
    }

    /**
     * Student index: upcoming/current/past based on quiz window in UTC.
     */
    public function studentIndexBuckets(int $studentId): array
    {
        $now = $this->nowUtc();

        $quizIds = Enrollment::query()
            ->where('student_id', $studentId)
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('course_id')
            ->toArray();

        $base = Quiz::query()
            ->whereIn('course_id', $quizIds)
            ->where('visibility', 'published');

        $upcoming = (clone $base)->where('start_at', '>', $now)->orderBy('start_at')->get();
        $current  = (clone $base)->where('start_at', '<=', $now)->where('end_at', '>=', $now)->orderBy('end_at')->get();
        $past     = (clone $base)->where('end_at', '<', $now)->orderByDesc('end_at')->get();

        return compact('upcoming', 'current', 'past');
    }
}
