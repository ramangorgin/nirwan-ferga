<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{

protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'gender',
        'birthdate',
        'country',
        'city',
        'timezone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',  
        'birthdate' => 'date',
        'role' => 'string',
        'gender' => 'string',
        'timezone' => 'string',
        'status' => 'string',
    ];

    /**
     * Hash password automatically if needed.
     */
    public function setPasswordAttribute($value)
    {
        if (!Hash::needsRehash($value)) {
            $this->attributes['password'] = $value;
            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }


    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function personalizedAssignments()
    {
        return $this->hasMany(AssignmentPersonalization::class, 'student_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function quizSubmissions()
    {
        return $this->hasMany(QuizSubmission::class, 'student_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function studentConversations()
    {
        return $this->hasMany(Conversation::class, 'student_id');
    }

    public function teacherConversations()
    {
        return $this->hasMany(Conversation::class, 'teacher_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function ticketMessages()
    {
        return $this->hasMany(TicketMessage::class, 'sender_id');
    }

    public function discountCodes()
    {
        return $this->hasMany(DiscountCode::class);
    }

    public function gradedSubmissions()
    {
        return $this->hasMany(Submission::class, 'graded_by');
    }

    public function createdQuizzes()
    {
        return $this->hasMany(Quiz::class, 'created_by');
    }

    public function createdQuizQuestions()
    {
        return $this->hasMany(QuizQuestion::class, 'created_by');
    }

    public function createdAssignmentPersonalizations()
    {
        return $this->hasMany(AssignmentPersonalization::class, 'created_by');
    }

    public function reviewedPayments()
    {
        return $this->hasMany(Payment::class, 'reviewed_by');
    }

     // ---- Relations ----
    public function notifications()
    {
        return $this->belongsToMany(\App\Models\Notification::class, 'notification_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    // ---- Helpers ----
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isTeacher(): bool { return $this->role === 'teacher'; }
    public function isStudent(): bool { return $this->role === 'student'; }

    public function isActive(): bool { return $this->status === 'active'; }
    public function isBanned(): bool { return $this->status === 'ban'; }

    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified_at !== null;
    }

}