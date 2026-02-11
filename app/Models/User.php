<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'status',
        'gender',
        'year_of_birth',
        'country',
        'city',
        'timezone'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'string',
    ];

    public function setPasswordAttribute($value)
    {
        if (! Hash::needsRehash($value)) {
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function broadcastNotifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user');
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

}