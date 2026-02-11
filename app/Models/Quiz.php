<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'quiz_type',
        'course_id',
        'created_by',
        'start_at',
        'end_at',
        'duration_minutes',
        'attempt_limit',
        'shuffle_questions',
        'shuffle_options',
        'auto_grade',
        'show_results_after_submissions',
        'show_correct_answers',
        'passing_score',
        'total_score_cached',
        'syllabus_tags',
        'requirements_text',
        'visibility'
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'duration_minutes' => 'integer',
        'attempt_limit' => 'integer',
        'passing_score' => 'integer',
        'total_score_cached' => 'integer',
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'auto_grade' => 'boolean',
        'show_results_after_submissions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'syllabus_tags' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function submissions()
    {
        return $this->hasMany(QuizSubmission::class);
    }

    // Helper Methods

    /**
     * Check if quiz is published
     */
    public function isPublished(): bool
    {
        return $this->visibility === 'published';
    }

    /**
     * Check if quiz is closed
     */
    public function isClosed(): bool
    {
        return $this->visibility === 'closed';
    }

    /**
     * Check if quiz is draft
     */
    public function isDraft(): bool
    {
        return $this->visibility === 'draft';
    }

    /**
     * Get total number of questions
     */
    public function totalQuestions(): int
    {
        return $this->questions()->count();
    }

    /**
     * Check if quiz is currently available for submission
     */
    public function isAvailable(): bool
    {
        $now = now();
        return $now->greaterThanOrEqualTo($this->start_at) && 
               $now->lessThanOrEqualTo($this->end_at) && 
               $this->isPublished();
    }

    /**
     * Check if quiz has started
     */
    public function hasStarted(): bool
    {
        return now()->greaterThanOrEqualTo($this->start_at);
    }

    /**
     * Check if quiz has ended
     */
    public function hasEnded(): bool
    {
        return now()->greaterThan($this->end_at);
    }

    /**
     * Check if auto grading is enabled
     */
    public function autoGrades(): bool
    {
        return $this->auto_grade === true;
    }

    /**
     * Check if results are shown after submission
     */
    public function showsResults(): bool
    {
        return $this->show_results_after_submissions === true;
    }

    /**
     * Check if correct answers are shown
     */
    public function showsCorrectAnswers(): bool
    {
        return $this->show_correct_answers === true;
    }

    /**
     * Check if quiz has passing score requirement
     */
    public function hasPassingScore(): bool
    {
        return $this->passing_score !== null;
    }

}

