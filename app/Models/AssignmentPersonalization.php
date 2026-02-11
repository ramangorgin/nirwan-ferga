<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentPersonalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'custom_title',
        'custom_description',
        'custom_type',
        'custom_options',
        'custom_correct_answer',
        'custom_deadline',
        'custom_score',
        'created_by'
    ];

    protected $casts = [
        'custom_options' => 'array',
        'custom_deadline' => 'datetime',
        'custom_score' => 'integer'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Methods

    /**
     * Check if personalization has custom deadline
     */
    public function hasCustomDeadline(): bool
    {
        return $this->custom_deadline !== null;
    }

    /**
     * Check if personalization has custom score
     */
    public function hasCustomScore(): bool
    {
        return $this->custom_score !== null;
    }

    /**
     * Check if personalization has custom options
     */
    public function hasCustomOptions(): bool
    {
        return $this->custom_options !== null;
    }

    /**
     * Get the effective deadline (custom or original)
     */
    public function getEffectiveDeadline()
    {
        return $this->custom_deadline ?? $this->assignment->deadline;
    }

    /**
     * Get the effective score (custom or original)
     */
    public function getEffectiveScore(): int
    {
        return $this->custom_score ?? $this->assignment->score;
    }

}