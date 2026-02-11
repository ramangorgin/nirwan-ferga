<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'created_by',
        'question_type',
        'question_text',
        'options',
        'correct_answer',
        'score',
        'order_index'
    ];

    protected $casts = [
        'options' => 'array',
        'score' => 'integer',
        'order_index' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }

    // Helper Methods

    /**
     * Check if question is multiple choice
     */
    public function isMCQ(): bool
    {
        return $this->question_type === 'mcq';
    }

    /**
     * Check if question is true/false
     */
    public function isTrueFalse(): bool
    {
        return $this->question_type === 'true_false';
    }

    /**
     * Check if question is fill in the blank
     */
    public function isFillBlank(): bool
    {
        return $this->question_type === 'fill_blank';
    }

    /**
     * Check if question is text type
     */
    public function isText(): bool
    {
        return $this->question_type === 'text';
    }

    /**
     * Check if question has options
     */
    public function hasOptions(): bool
    {
        return $this->options !== null && count($this->options) > 0;
    }

    /**
     * Get the number of options
     */
    public function optionCount(): int
    {
        return $this->hasOptions() ? count($this->options) : 0;
    }

    /**
     * Check if question has correct answer
     */
    public function hasCorrectAnswer(): bool
    {
        return $this->correct_answer !== null;
    }

}

