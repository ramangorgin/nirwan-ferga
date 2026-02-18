<?php

namespace App\Services\Quizzes;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Hekmatinasser\Verta\Verta;

class QuizService
{
    /**
     * Convert Jalali datetime string (entered by user) to UTC Carbon.
     * We interpret the input as being in the user's timezone.
     */
    public function jalaliToUtc(string $jalali, string $userTz): Carbon
    {
        try {
            // Verta can parse Jalali string; then convert to Carbon
            // Common input formats: "1404/01/01 12:30" or similar.
            $v = Verta::parse($jalali);
            $c = $v->datetime(); // Carbon instance (usually)
            return $c->setTimezone($userTz)->utc();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'datetime' => ['فرمت تاریخ/زمان نامعتبر است.'],
            ]);
        }
    }

    public function store(array $data, int $actorUserId, string $actorTz): Quiz
    {
        return DB::transaction(function () use ($data, $actorUserId, $actorTz) {
            $startUtc = $this->jalaliToUtc($data['start_at'], $actorTz);
            $endUtc   = $this->jalaliToUtc($data['end_at'], $actorTz);

            if ($endUtc->lessThanOrEqualTo($startUtc)) {
                throw ValidationException::withMessages([
                    'end_at' => ['زمان پایان باید بعد از زمان شروع باشد.'],
                ]);
            }

            $quiz = Quiz::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'quiz_type' => $data['quiz_type'],
                'course_id' => $data['course_id'],
                'created_by' => $actorUserId,
                'start_at' => $startUtc,
                'end_at' => $endUtc,
                'duration_minutes' => (int) $data['duration_minutes'],
                'attempt_limit' => (int) ($data['attempt_limit'] ?? 1),

                'shuffle_questions' => (bool) ($data['shuffle_questions'] ?? false),
                'shuffle_options' => (bool) ($data['shuffle_options'] ?? false),
                'auto_grade' => (bool) ($data['auto_grade'] ?? true),
                'show_results_after_submissions' => (bool) ($data['show_results_after_submissions'] ?? true),
                'show_correct_answers' => (bool) ($data['show_correct_answers'] ?? true),

                'passing_score' => $data['passing_score'] ?? null,
                'total_score_cached' => null,
                'syllabus_tags' => $data['syllabus_tags'] ?? null,
                'requirements_text' => $data['requirements_text'] ?? null,
                'visibility' => $data['visibility'] ?? 'draft',
            ]);

            return $quiz;
        });
    }

    public function update(Quiz $quiz, array $data, string $actorTz): Quiz
    {
        return DB::transaction(function () use ($quiz, $data, $actorTz) {
            if (array_key_exists('start_at', $data)) {
                $quiz->start_at = $this->jalaliToUtc($data['start_at'], $actorTz);
            }
            if (array_key_exists('end_at', $data)) {
                $quiz->end_at = $this->jalaliToUtc($data['end_at'], $actorTz);
            }

            $quiz->fill(collect($data)->except(['start_at', 'end_at'])->toArray());

            // Ensure booleans are set if present
            foreach ([
                'shuffle_questions', 'shuffle_options', 'auto_grade',
                'show_results_after_submissions', 'show_correct_answers'
            ] as $b) {
                if (array_key_exists($b, $data)) {
                    $quiz->{$b} = (bool) $data[$b];
                }
            }

            if ($quiz->end_at->lessThanOrEqualTo($quiz->start_at)) {
                throw ValidationException::withMessages([
                    'end_at' => ['زمان پایان باید بعد از زمان شروع باشد.'],
                ]);
            }

            $quiz->save();

            // Recalculate total_score_cached whenever quiz changes (safe)
            $this->recalculateTotalScore($quiz);

            return $quiz->fresh();
        });
    }

    public function delete(Quiz $quiz): void
    {
        DB::transaction(fn () => $quiz->delete());
    }

    public function addQuestion(Quiz $quiz, array $data, int $actorUserId): QuizQuestion
    {
        return DB::transaction(function () use ($quiz, $data, $actorUserId) {
            $order = (int) ($data['order_index'] ?? ($quiz->questions()->max('order_index') ?? 0) + 1);

            $q = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'created_by' => $actorUserId,
                'question_type' => $data['question_type'],
                'question_text' => $data['question_text'],
                'options' => $data['options'] ?? null,
                'correct_answer' => $data['correct_answer'] ?? null,
                'score' => (int) ($data['score'] ?? 1),
                'order_index' => $order,
            ]);

            $this->recalculateTotalScore($quiz);

            return $q;
        });
    }

    public function updateQuestion(QuizQuestion $question, array $data): QuizQuestion
    {
        return DB::transaction(function () use ($question, $data) {
            $question->fill($data);
            $question->save();

            $this->recalculateTotalScore($question->quiz);

            return $question->fresh();
        });
    }

    public function deleteQuestion(QuizQuestion $question): void
    {
        DB::transaction(function () use ($question) {
            $quiz = $question->quiz;
            $question->delete();
            $this->recalculateTotalScore($quiz);
        });
    }

    public function recalculateTotalScore(?Quiz $quiz): void
    {
        if (!$quiz) return;

        $total = (int) $quiz->questions()->sum('score');
        $quiz->update(['total_score_cached' => $total]);
    }
}
