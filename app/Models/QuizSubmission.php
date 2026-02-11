<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'quiz_id',
        'attempt_number',
        'started_at',
        'finished_at',
        'total_score',
        'passed'
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'total_score' => 'integer',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'submission_id');
    }

    // Helper Methods

    /**
     * Check if submission is completed
     */
    public function isCompleted(): bool
    {
        return $this->finished_at !== null;
    }

    /**
     * Get the score
     */
    public function score(): ?int
    {
        return $this->total_score;
    }

    /**
     * Get score percentage
     */
    public function scorePercentage(): float
    {
        if (!$this->quiz || $this->quiz->total_score_cached === 0) {
            return 0;
        }
        
        return ($this->total_score / $this->quiz->total_score_cached) * 100;
    }

    /**
     * Check if submission passed
     */
    public function hasPassed(): bool
    {
        return $this->passed === true;
    }

    /**
     * Get submission duration in minutes
     */
    public function durationInMinutes(): ?int
    {
        if ($this->started_at && $this->finished_at) {
            return $this->started_at->diffInMinutes($this->finished_at);
        }
        
        return null;
    }

    /**
     * Check if submission exceeded time limit
     */
    public function exceededTimeLimit(): bool
    {
        $duration = $this->durationInMinutes();
        return $duration && $duration > $this->quiz->duration_minutes;
    }

    /**
     * Check if submission is pending (started but not finished)
     */
    public function isPending(): bool
    {
        return $this->started_at !== null && $this->finished_at === null;
    }

}

