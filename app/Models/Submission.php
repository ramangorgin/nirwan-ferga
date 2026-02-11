<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'enrollment_id',
        'attempt_number',
        'status',
        'submitted_at',
        'graded_at',
        'graded_by',
        'auto_graded',
        'score_obtained',
        'max_score_cached',
        'is_late',
        'answer_text',
        'answer_json',
        'file_path',
        'feedback_text'
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'score_obtained' => 'integer',
        'max_score_cached' => 'integer',
        'is_late' => 'boolean',
        'auto_graded' => 'boolean',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'answer_json' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function gradedBy()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Helper Methods

    /**
     * Check if submission is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if submission is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if submission is graded
     */
    public function isGraded(): bool
    {
        return $this->status === 'graded';
    }

    /**
     * Check if submission is late
     */
    public function isLate(): bool
    {
        return $this->is_late === true;
    }

    /**
     * Check if submission was auto graded
     */
    public function isAutoGraded(): bool
    {
        return $this->auto_graded === true;
    }

    /**
     * Get the score percentage
     */
    public function getScorePercentage(): float
    {
        if ($this->max_score_cached === 0 || $this->score_obtained === null) {
            return 0;
        }
        
        return ($this->score_obtained / $this->max_score_cached) * 100;
    }

    /**
     * Check if submission passed
     */
    public function isPassed(): bool
    {
        return $this->getScorePercentage() >= 50;
    }

    /**
     * Get submission feedback
     */
    public function hasFeedback(): bool
    {
        return $this->feedback_text !== null;
    }

}

