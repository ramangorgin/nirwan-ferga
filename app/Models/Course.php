<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hekmatinasser\Verta\Verta;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'video_path',
        'poster_path',
        'level',
        'teaching_in_kurdish',
        'capacity_min',
        'capacity_max',
        'registration_deadline',
        'start_date',
        'end_date',
        'days_of_week',
        'start_time',
        'session_duration',
        'sessions_count',
        'syllabus',
        'price',
        'card_number',
        'card_shaba_number',
        'card_owner_name',
        'bank_name',
        'is_active',
        'status',
        'teacher_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'teaching_in_kurdish' => 'boolean',
        'days_of_week' => 'array',
        'syllabus' => 'array',
        'registration_deadline' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class , 'teacher_id');
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_course');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // Helper Methods

    /**
     * Check if registration period is still open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->registration_deadline > now() && $this->status === 'registration_open';
    }

    /**
     * Check if course is full
     */
    public function isFull(): bool
    {
        return $this->enrollments()->where('status', '!=', 'rejected')->where('status', '!=', 'cancelled')->count() >= $this->capacity_max;
    }

    /**
     * Check if course has started
     */
    public function hasStarted(): bool
    {
        return now()->greaterThanOrEqualTo($this->start_date);
    }

    /**
     * Check if course has ended
     */
    public function hasEnded(): bool
    {
        return now()->greaterThan($this->end_date);
    }

    /**
     * Check if course is being taught in Kurdish
     */
    public function isTaughtInKurdish(): bool
    {
        return $this->teaching_in_kurdish === true;
    }

    /**
     * Check if user is enrolled in this course
     */
    public function isEnrolled(User $user): bool
    {
        return $this->enrollments()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->exists();
    }

    /**
     * Get remaining capacity
     */
    public function remainingCapacity(): int
    {
        $enrolled = $this->enrollments()
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
        
        return max(0, $this->capacity_max - $enrolled);
    }

    // Date formatting accessors

    public function getStartDateJalaliAttribute(): ?string
    {
        return $this->start_date
            ? Verta::instance($this->start_date)->format('Y/m/d')
            : null;
    }

    public function getEndDateJalaliAttribute(): ?string
    {
        return $this->end_date
            ? Verta::instance($this->end_date)->format('Y/m/d')
            : null;
    }

    public function getRegistrationDeadlineJalaliAttribute(): ?string
    {
        return $this->registration_deadline
            ? Verta::instance($this->registration_deadline)->format('Y/m/d')
            : null;
    }

}
