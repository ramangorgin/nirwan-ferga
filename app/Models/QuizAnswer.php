<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'question_id',
        'student_answer',
        'score_awarded'
    ];

    protected $casts = [
        'score_awarded' => 'integer',
    ];

    public function submission()
    {
        return $this->belongsTo(QuizSubmission::class);
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    // Helper Methods

    /**
     * Check if answer is correct
     */
    public function isCorrect(): bool
    {
        if (!$this->question) {
            return false;
        }

        // For text answers, case-insensitive comparison
        if ($this->question->isText()) {
            return strtolower(trim($this->student_answer)) === 
                   strtolower(trim($this->question->correct_answer));
        }

        // For other types, direct comparison
        return $this->student_answer === $this->question->correct_answer;
    }

    /**
     * Check if answer was scored
     */
    public function isScored(): bool
    {
        return $this->score_awarded !== null;
    }

    /**
     * Check if answer matches expected answer
     */
    public function matches(): bool
    {
        return $this->isCorrect();
    }

    /**
     * Get the maximum possible score for this question
     */
    public function maxScore(): int
    {
        return $this->question->score ?? 1;
    }

    /**
     * Get score percentage
     */
    public function scorePercentage(): float
    {
        $maxScore = $this->maxScore();
        if ($maxScore === 0) {
            return 0;
        }

        return ($this->score_awarded / $maxScore) * 100;
    }

}

