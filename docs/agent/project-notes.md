# Project Notes — Nirwan-ferga (Frontend Data & Requirements)

This document contains aggregated notes about what each Blade screen needs from backend.
Before generating or updating any Blade view, the agent MUST read this file once, then re-check relevant sections per feature.

## Project Overview
Nirwan-ferga is an online Kurdish (Sorani) language learning institute platform with a full CMS for persian students. the UI of the project is entierly in Persian unless if the question or the answer (Quizes or Submission/Assignments) is in kurdish

Core modules:
- Courses, Sessions, Quizzes, Assignments (Homework)
- Announcements, Messaging/Communication, Tickets
- Users: Admin, Teacher, Student roles
- Enrollment & participation flows

## Branding & Naming
Display name in UI: "نیروان فێرگە"
Project code name: "Nirwan-ferga"
Persian UI uses Kurdish display name.

## Global Frontend Requirements
- RTL everywhere
- No emoji (icons only via x-ui.icon)
- Persian digits everywhere (@faNum)
- Jalali date formatting everywhere (verta)
- Jalali date inputs always use data-jdp
- Confirmations must use SweetAlert2 global data-confirm binding
- Action's Messages to the user: toastr

## Role-Based UI
We have 3 dashboards, each with its own layout and navigation:
- Admin: management/CMS
- Teacher: manage own courses, sessions, assignments, quizzes, grading, communication
- Student: view courses, enroll, access sessions, do quizzes/homework, messaging, ticketing and etc

Views must live under:
- resources/views/admin/*
- resources/views/teacher/*
- resources/views/student/*

## Page Patterns (Important)
Not every model/table has full CRUD views.
Many operations occur within a single screen.
Example:
- Student enrolls into a course inside the course show page (student role),
  not via separate create/store screens.

The agent must follow routes/controllers instead of assuming CRUD.

## Data Passing Conventions
The backend already exists (controllers/services/requests/policies).
The agent must:
1) Read routes/web.php
2) Identify controller actions for the feature
3) Read the controller + used FormRequests + used Services
4) Implement Blade to match the exact data passed to the view used by componnents we have in the project's view/ dir

Do NOT guess field names. Use actual backend structures.

## Common Entities & Expectations (High-level)
### Courses
- Admin: full management (create/edit/publish/pricing/teacher assignment)
- Teacher: view assigned courses, manage content
- Student: list courses, view course details, enroll, view sessions

### Sessions
- Linked to course
- Scheduling uses Jalali dates in UI
- Teacher manages sessions; student views upcoming/past sessions

### Quizzes & Homework
- Teacher creates & assigns
- Student submits answers and uploads attachments (if supported)
- Teacher reviews/submits grades and feedback
- AutoGrading is availible in the backend for many types of questions

### Announcements & Messaging
- Teachers/admins post announcements to subsets of students
- Rich text often required (ckeditor)
- Some pages may need multi-select recipients:
  - NOTE: select2 was removed; use either:
    - a custom Livewire multi-select component (preferred), OR
    - a simple multi-select <select multiple> as MVP
  TODO: decide the final recipients selector UX.

## JS Library Usage Policy (How to decide)
- CKEditor:
  Use for long rich-text fields only, mostly for the teacher and admin for courses or sessions or tickets
- JalaliDatePicker:
  Use on every date input field.
- SweetAlert2:
  Use for confirms (delete/irreversible actions/important actions).
- Toastr:
  Use for feedback messages.

## Pagination
Laravel pagination in views must be Persian-digit friendly.
If using paginator links, use a custom pagination view that renders @faNum for page numbers.
TODO: confirm whether pagination view is already published and customized.

## Attachments / Uploads
If file uploads exist:
- Use the existing UI components: dropzone + file-item
- Form must use multipart/form-data
- Validate and store using backend expectations
TODO: specify allowed file types/sizes if needed.

## Notes Source
Below is a full list of each parameter that should be sent from the backend to the frontend for each view file and table. The agent must consider these notes very carefully:
Below is a **single, comprehensive “Blade Data Cheatsheet”** you can paste into a text file and use later while building the frontend.
Each section shows **what variables you should pass to each Blade** and gives a **small PHP snippet (`<?php ... ?>`)** you can follow.

> Notes:
>
> * I’m using **Laravel conventions**: controller returns `view(..., [...])`.
> * I’m using **UTC storage** in DB and **display in user timezone in Blade**.
> * For Jalali display, I use `verta(...)`.
> * For “role awareness”, I assume you have `auth()->user()->role` and `auth()->user()->timezone`.

---

# 0) Global Blade Helpers (put in a shared include or just copy when needed)

```php
<?php
// 1) Get user timezone (fallback to app timezone)
$tz = (string) (auth()->user()->timezone ?? config('app.timezone', 'UTC'));

// 2) Convert UTC datetime column to user tz and format in Jalali
// Usage: $jalali = jalali_dt($model->created_at);
function jalali_dt($utcCarbon, $tz = null, $format = 'Y/m/d H:i')
{
    if (!$utcCarbon) return null;
    $tz = $tz ?? (auth()->user()->timezone ?? config('app.timezone', 'UTC'));
    return verta($utcCarbon->copy()->setTimezone($tz))->format($format);
}

// 3) Convert UTC date+time columns (stored separately) to a user-tz datetime (for ClassSession/Course start_time)
function combine_utc_date_time_to_local($dateCarbon, $timeString, $tz = null)
{
    if (!$dateCarbon || !$timeString) return null;
    $tz = $tz ?? (auth()->user()->timezone ?? config('app.timezone', 'UTC'));

    $utc = \Carbon\Carbon::createFromFormat(
        'Y-m-d H:i:s',
        $dateCarbon->format('Y-m-d') . ' ' . $timeString,
        'UTC'
    );

    return $utc->setTimezone($tz);
}
```

---

# 1) Courses

## 1.1 `admin/courses/index.blade.php`

**Need:**

* paginated courses list
* for teacher: only their courses
* optional filters

```php
<?php
return view('admin.courses.index', [
    'courses' => $courses, // LengthAwarePaginator<Course> with ->with('teacher') if needed
    'filters' => [
        'status' => request('status'),
        'q' => request('q'),
    ],
]);
```

## 1.2 `admin/courses/create.blade.php`

**Need:**

* list of teachers (if admin)
* course types / status enums
* default booleans

```php
<?php
return view('admin.courses.create', [
    'teachers' => $teachers ?? [], // Collection<User> (role=teacher), only for admin
    'enums' => [
        'status' => ['draft', 'published', 'closed'],
        // add any course type enums you have
    ],
    'defaults' => [
        'is_active' => true,
    ],
]);
```

## 1.3 `admin/courses/edit.blade.php`

**Need:**

* course model
* teachers list (admin)
* same enums

```php
<?php
return view('admin.courses.edit', [
    'course' => $course, // Course
    'teachers' => $teachers ?? [],
    'enums' => [
        'status' => ['draft', 'published', 'closed'],
    ],
]);
```

## 1.4 `admin/courses/show.blade.php`

**Need:**

* course with sessions, enrollments counts, etc.

```php
<?php
return view('admin.courses.show', [
    'course' => $course->load([
        'teacher',
        'classSessions',
        // optionally 'enrollments.student'
    ]),
    'stats' => [
        'sessions_count' => $course->classSessions()->count(),
        'enrollments_count' => $course->enrollments()->count(),
    ],
]);
```

**Timezone display (example in blade)**

```php
<?php
// registration_deadline stored UTC datetime in DB
$registrationDeadlineJalali = jalali_dt($course->registration_deadline, $tz);

// if course has start_date (date) + start_time (UTC time string)
$startLocal = combine_utc_date_time_to_local($course->start_date, $course->start_time, $tz);
$startLocalJalali = $startLocal ? verta($startLocal)->format('Y/m/d H:i') : null;
```

---

# 2) Class Sessions

## 2.1 `admin/class-sessions/index.blade.php` (if exists)

**Need:**

* sessions for a course (paginated)

```php
<?php
return view('admin.class-sessions.index', [
    'course' => $course,
    'sessions' => $sessions, // paginator or collection
]);
```

## 2.2 `admin/class-sessions/create.blade.php`

**Need:**

* course
* enums for status

```php
<?php
return view('admin.class-sessions.create', [
    'course' => $course,
    'enums' => [
        'status' => ['scheduled', 'held', 'cancelled'], // replace with your real enum values
    ],
]);
```

## 2.3 `admin/class-sessions/edit.blade.php`

```php
<?php
return view('admin.class-sessions.edit', [
    'course' => $course,
    'session' => $classSession,
    'enums' => [
        'status' => ['scheduled', 'held', 'cancelled'],
    ],
]);
```

## 2.4 `admin/class-sessions/show.blade.php` (THE MAIN PAGE)

This is the page you said will contain:

* session details
* session materials upload
* attendances (later)
* assignments + submissions list + grading (already built)
* (later: tickets etc)

**Need:**

* session + course
* materials list
* assignments list
* submissions grouped by assignment (teacher view)
* optionally: enrolled students list (for attendance / personalizations)

```php
<?php
return view('admin.class-sessions.show', [
    'session' => $classSession->load([
        'course',
        'materials',
        'assignments',
        // If you show submissions here:
        'assignments.submissions.student',
    ]),

    // For SessionMaterial section
    'materials' => $classSession->materials()->latest()->get(),

    // For Assignment section
    'assignments' => $classSession->assignments()->latest()->get(),

    // For showing submissions under each assignment
    'submissionsByAssignment' => $submissionsByAssignment ?? [], // [assignmentId => Collection<Submission>]

    // For later attendance UI / personalized assignments UI
    'students' => $students ?? collect(), // enrolled students for this course
]);
```

**Timezone display example (session date+time stored in UTC parts):**

```php
<?php
$startLocal = combine_utc_date_time_to_local($session->session_date, $session->start_time, $tz);
$endLocal   = combine_utc_date_time_to_local($session->session_date, $session->end_time, $tz);

$sessionStartJalali = $startLocal ? verta($startLocal)->format('Y/m/d H:i') : null;
$sessionEndJalali   = $endLocal ? verta($endLocal)->format('Y/m/d H:i') : null;
```

---

# 3) Session Materials

## 3.1 On `admin/class-sessions/show.blade.php` (Materials block)

**Need:**

* materials list
* upload form routes
* file types mapping (optional)

```php
<?php
return view('admin.class-sessions.show', [
    // ...
    'materials' => $classSession->materials()->latest()->get(),
    'routes' => [
        'store_material' => route('session-materials.store'),
        'update_material' => fn($m) => route('session-materials.update', $m),
        'delete_material' => fn($m) => route('session-materials.destroy', $m),
    ],
    'fileTypeLabels' => [
        'pdf' => 'PDF',
        'audio' => 'Audio',
        'video' => 'Video',
        'slides' => 'Slides',
        'file' => 'File',
    ],
]);
```

---

# 4) Enrollments

## 4.1 `admin/enrollments/index.blade.php`

**Need:**

* enrollments list with course/student
* status enums
* payment_status enums

```php
<?php
return view('admin.enrollments.index', [
    'enrollments' => $enrollments->load(['course', 'student']),
    'enums' => [
        'status' => ['pending', 'confirmed', 'completed', 'cancelled'],
        'payment_status' => ['unpaid', 'paid', 'partial', 'refunded'], // adjust to your enum
    ],
]);
```

## 4.2 `admin/enrollments/create.blade.php` (manual enroll form)

**Need:**

* courses list
* students list
* discount codes maybe

```php
<?php
return view('admin.enrollments.create', [
    'courses' => $courses,   // Collection<Course>
    'students' => $students, // Collection<User> role=student
    'defaults' => [
        'status' => 'confirmed',
        'payment_status' => 'paid',
    ],
]);
```

## 4.3 `admin/enrollments/edit.blade.php`

```php
<?php
return view('admin.enrollments.edit', [
    'enrollment' => $enrollment->load(['course', 'student']),
    'enums' => [
        'status' => ['pending', 'confirmed', 'completed', 'cancelled'],
        'payment_status' => ['unpaid', 'paid', 'partial', 'refunded'],
    ],
]);
```

---

# 5) Discount Codes

## 5.1 `admin/discount-codes/index.blade.php`

```php
<?php
return view('admin.discount-codes.index', [
    'discountCodes' => $discountCodes, // paginator
    'enums' => [
        'type' => ['percent', 'fixed'],
        'status' => ['active', 'inactive'],
    ],
]);
```

## 5.2 `admin/discount-codes/create.blade.php`

```php
<?php
return view('admin.discount-codes.create', [
    'enums' => [
        'type' => ['percent', 'fixed'],
        'status' => ['active', 'inactive'],
    ],
]);
```

## 5.3 `admin/discount-codes/edit.blade.php`

```php
<?php
return view('admin.discount-codes.edit', [
    'discountCode' => $discountCode,
    'enums' => [
        'type' => ['percent', 'fixed'],
        'status' => ['active', 'inactive'],
    ],
]);
```

---

# 6) Assignments (Teacher/Admin)

## 6.1 Inside `admin/class-sessions/show.blade.php` (Assignments block)

**Need:**

* assignment types enum
* status enum
* allow_late default
* form endpoints

```php
<?php
return view('admin.class-sessions.show', [
    // ...
    'assignmentEnums' => [
        'type' => ['text', 'mcq', 'fill_blank', 'translation', 'file'],
        'status' => ['draft', 'published', 'closed'],
    ],
    'assignmentDefaults' => [
        'score' => 1,
        'allow_late' => false,
        'status' => 'draft',
    ],
    'routes' => [
        'store_assignment' => route('assignments.store'),
        'update_assignment' => fn($a) => route('assignments.update', $a),
        'delete_assignment' => fn($a) => route('assignments.destroy', $a),
        'store_personalizations' => fn($a) => route('assignments.personalizations.store', $a),
    ],
]);
```

**Jalali deadline display (deadline stored UTC):**

```php
<?php
$deadlineJalali = jalali_dt($assignment->deadline, $tz);
```

---

# 7) Submissions (Student + Teacher grading)

## 7.1 Student submission form (inside some page, likely session show)

**Need:**

* assignment
* attempt limit (config)
* route

```php
<?php
return view('student.assignments.submit', [
    'assignment' => $assignment,
    'attemptLimit' => config('assignments.attempt_limit', 3),
    'routeSubmit' => route('assignments.submissions.store', $assignment),
]);
```

## 7.2 Teacher view all submissions for a session

You created: `GET class-sessions/{class_session}/submissions`

```php
<?php
return view('admin.class-sessions.submissions', [
    'session' => $classSession->load('course', 'assignments'),
    'submissionsByAssignment' => $submissionsByAssignment, // [assignmentId => Collection<Submission with student>]
    'gradeRoute' => fn($submission) => route('submissions.grade', $submission),
]);
```

**Show submitted_at/graded_at (stored UTC):**

```php
<?php
$submittedJalali = jalali_dt($submission->submitted_at, $tz);
$gradedJalali = jalali_dt($submission->graded_at, $tz);
```

---

# 8) Quizzes (Admin/Teacher)

You said these are EXACT admin blades:

* `admin/quizzes/index`
* `admin/quizzes/create`
* `admin/quizzes/edit`
* `admin/quizzes/show`

## 8.1 `admin/quizzes/index.blade.php`

```php
<?php
return view('admin.quizzes.index', [
    'quizzes' => $quizzes, // paginator with ->with('course')
    'enums' => [
        'visibility' => ['draft', 'published', 'closed'],
        'quiz_type' => ['normal_quiz', 'midterm', 'final_exam', 'placement_test'],
    ],
]);
```

## 8.2 `admin/quizzes/create.blade.php`

**Need courses list for dropdown** (teacher-limited if teacher)

```php
<?php
return view('admin.quizzes.create', [
    'courses' => $courses, // Collection<Course> filtered by teacher if needed
    'enums' => [
        'visibility' => ['draft', 'published', 'closed'],
        'quiz_type' => ['normal_quiz', 'midterm', 'final_exam', 'placement_test'],
    ],
    'defaults' => [
        'attempt_limit' => 1,
        'duration_minutes' => 30,
        'shuffle_questions' => false,
        'shuffle_options' => false,
        'auto_grade' => true,
        'show_results_after_submissions' => true,
        'show_correct_answers' => false,
    ],
]);
```

## 8.3 `admin/quizzes/edit.blade.php`

This page usually manages:

* quiz core fields
* add/update/delete questions

```php
<?php
return view('admin.quizzes.edit', [
    'quiz' => $quiz->load(['course', 'questions']),
    'courses' => $courses ?? collect(), // if you allow changing course
    'enums' => [
        'visibility' => ['draft', 'published', 'closed'],
        'quiz_type' => ['normal_quiz', 'midterm', 'final_exam', 'placement_test'],
        'question_type' => ['mcq', 'true_false', 'fill_blank', 'text'],
    ],
    'routes' => [
        'store_question' => route('admin.quizzes.questions.store', $quiz),
        'update_question' => fn($q) => route('admin.quiz-questions.update', $q),
        'delete_question' => fn($q) => route('admin.quiz-questions.destroy', $q),
    ],
]);
```

## 8.4 `admin/quizzes/show.blade.php`

```php
<?php
return view('admin.quizzes.show', [
    'quiz' => $quiz->load(['course', 'questions']),
]);
```

**Quiz start/end display (stored UTC datetime):**

```php
<?php
$startJalali = jalali_dt($quiz->start_at, $tz);
$endJalali = jalali_dt($quiz->end_at, $tz);
```

---

# 9) Quizzes (Student)

You said student blades:

* `student/quizzes/index` (upcoming/current/past in one page)
* `student/quizzes/show` (take + answer in one page)

## 9.1 `student/quizzes/index.blade.php`

```php
<?php
return view('student.quizzes.index', [
    'upcoming' => $upcoming, // Collection<Quiz>
    'current'  => $current,  // Collection<Quiz>
    'past'     => $past,     // Collection<Quiz>
]);
```

**Show start/end local Jalali:**

```php
<?php
$startLocal = $quiz->start_at->copy()->setTimezone($tz);
$endLocal = $quiz->end_at->copy()->setTimezone($tz);

$startJalali = verta($startLocal)->format('Y/m/d H:i');
$endJalali = verta($endLocal)->format('Y/m/d H:i');
```

## 9.2 `student/quizzes/show.blade.php`

**Need:**

* quiz + questions (maybe shuffled in backend)
* submission (if you use pending-submission flow) OR just quiz
* booleans: shuffle_options
* route submit

```php
<?php
return view('student.quizzes.show', [
    'quiz' => $quiz->load('questions'),
    // If you implement "pending submission" start flow:
    'submission' => $submission ?? null,

    'routes' => [
        'submit' => route('student.quizzes.submit', $quiz),
    ],

    // helpful for blade logic:
    'flags' => [
        'shuffle_options' => (bool) $quiz->shuffle_options,
        'show_correct_answers' => (bool) $quiz->show_correct_answers,
        'show_results_after_submissions' => (bool) $quiz->show_results_after_submissions,
    ],
]);
```

**Countdown timer base (local):**

```php
<?php
$startLocal = $quiz->start_at->copy()->setTimezone($tz);
$endLocal = $quiz->end_at->copy()->setTimezone($tz);

// You can pass these as ISO strings for JS timers:
$startIso = $startLocal->toIso8601String();
$endIso = $endLocal->toIso8601String();
```

---

# 10) (Optional) “Master enums pack” you can pass everywhere

If you like centralizing, you can pass a single config array to many views:

```php
<?php
$enums = [
    'assignment' => [
        'type' => ['text', 'mcq', 'fill_blank', 'translation', 'file'],
        'status' => ['draft', 'published', 'closed'],
    ],
    'quiz' => [
        'visibility' => ['draft', 'published', 'closed'],
        'quiz_type' => ['normal_quiz', 'midterm', 'final_exam', 'placement_test'],
        'question_type' => ['mcq', 'true_false', 'fill_blank', 'text'],
    ],
    'attendance' => [
        'status' => ['present', 'absent', 'late', 'excused'],
    ],
    'discount' => [
        'type' => ['percent', 'fixed'],
    ],
    'enrollment' => [
        'status' => ['pending', 'confirmed', 'completed', 'cancelled'],
        'payment_status' => ['unpaid', 'paid', 'partial', 'refunded'],
    ],
];
```

## 11) Blade “Data to Pass” PHP Snippets

You said you want “PHP code we need for blades” — below are the exact variables you should have.

### 11.1 conversations/index.blade.php

Purpose: list conversations with other participant + unread count + last message preview.

```php
<?php
return view('conversations.index', [
    'conversations' => $conversations, // paginator
    'tz' => (string) (auth()->user()->timezone ?? config('app.timezone', 'UTC')),

    // Helpers for Blade
    'routes' => [
        'open' => fn($c) => route('conversations.show', $c),
        'start' => route('conversations.store'),
    ],
]);
```

In blade you can do:

- `$conv->otherParticipant(auth()->user())`
- `$conv->unreadCountFor(auth()->user())`
- `$conv->lastMessage()`

If you want to avoid N+1 later, we can optimize with eager loading of last message.

### 11.2 conversations/show.blade.php

Purpose: show messages + send form + attachments.

```php
<?php
return view('conversations.show', [
    'conversation' => $conversation->load(['student', 'teacher', 'course']),
    'messages' => $messages, // paginator with sender
    'otherUser' => $otherUser,
    'tz' => (string) (auth()->user()->timezone ?? config('app.timezone', 'UTC')),

    'routes' => [
        'send' => route('conversations.messages.store', $conversation),
        'mark_read' => route('conversations.read', $conversation),
        'back' => route('conversations.index'),
    ],

    // Optional: file accept list for UI (you can expand)
    'ui' => [
        'attachment_max_mb' => 10,
    ],
]);
```

Datetime display in blade (UTC → user timezone → Jalali):

```php
<?php
$tz = $tz ?? (auth()->user()->timezone ?? 'UTC');
$sentAtJalali = verta($message->created_at->copy()->setTimezone($tz))->format('Y/m/d H:i');

$readAtJalali = $message->read_at
    ? verta($message->read_at->copy()->setTimezone($tz))->format('Y/m/d H:i')
    : null;
```

### 11.3 conversations/create.blade.php (or modal in index)

If you have a page like `conversations/create.blade.php` or a modal inside index, you need:

```php
<?php
return view('conversations.create', [
    'teachers' => $teachers, // Collection<User> role=teacher
    'courses' => $courses ?? collect(), // optional, only show courses student is enrolled in
    'routes' => [
        'store' => route('conversations.store'),
    ],
]);
```

(You can also embed this inside `index.blade.php` as a modal; then pass `teachers`/`courses` there.)

## 12) Conversation Notes (Production-safe)

- **Read timestamps:** always set `read_at = now('UTC')` in services/models.
- **File storage:** attachments are stored under `public/conversations/{conversation_id}/messages/{message_id}/...`.
- **Download URL:**

```php
Storage::disk('public')->url($message->attachment_path)
```

- **Notifications:** every message send triggers both `NotificationService` and `SmsService`.
- **Kurdish messages:** stored as text, so Kurdish content is fully supported.

## 13) Tickets — Blade Data Snippets

### 13.1 tickets/index.blade.php

```php
<?php
return view('tickets.index', [
    'tickets' => $tickets, // paginator with user + assignedTo
    'enums' => [
        'priority' => ['low', 'medium', 'high'],
        'status' => ['open', 'in_progress', 'answered', 'closed'],
    ],
    'routes' => [
        'create' => route('tickets.create'),
        'show' => fn($t) => route('tickets.show', $t),
    ],
]);
```

### 13.2 tickets/create.blade.php

```php
<?php
return view('tickets.create', [
    'enums' => [
        'priority' => ['low', 'medium', 'high'],
    ],
    'defaults' => [
        'priority' => 'medium',
    ],
    'routes' => [
        'store' => route('tickets.store'),
    ],
    'ui' => [
        'attachment_max_mb' => 10,
    ],
]);
```

### 13.3 tickets/show.blade.php

```php
<?php
$tz = (string) (auth()->user()->timezone ?? config('app.timezone', 'UTC'));

return view('tickets.show', [
    'ticket' => $ticket->load(['user', 'assignedTo']),
    'messages' => $messages, // paginator with sender
    'tz' => $tz,

    'enums' => [
        'priority' => ['low', 'medium', 'high'],
        'status' => ['open', 'in_progress', 'answered', 'closed'],
    ],

    'routes' => [
        'send_message' => route('tickets.messages.store', $ticket),
        'close' => route('tickets.close', $ticket),

        // admin-only form
        'admin_update' => route('tickets.adminUpdate', $ticket),
    ],
]);
```

For attachment URLs inside Blade:

```php
<?php
$url = $message->attachment_path
    ? \Illuminate\Support\Facades\Storage::disk('public')->url($message->attachment_path)
    : null;
```

Datetime display (UTC → user timezone → Jalali):

```php
<?php
$createdJalali = verta($message->created_at->copy()->setTimezone($tz))->format('Y/m/d H:i');
```

### 13.4 Notes about datetime conversions

Tickets/ticket_messages do not have Jalali user-entered datetime fields.

- We store timestamps normally (Laravel uses app timezone; best practice is DB UTC, which you’re using).
- For display: use UTC → user timezone → Jalali (`verta()`).
- Attachments are stored in `public/tickets/{ticket_id}/messages/{message_id}/...`.

## 14) Announcements — Blade Data Snippets

### 14.1 admin/announcements/index.blade.php

```php
<?php
return view('admin.announcements.index', [
    'announcements' => $announcements, // paginator with author, courses
    'routes' => [
        'create' => route('admin.announcements.create'),
        'edit' => fn($a) => route('admin.announcements.edit', $a),
        'show' => fn($a) => route('admin.announcements.show', $a),
        'delete' => fn($a) => route('admin.announcements.destroy', $a),
    ],
]);
```

### 14.2 admin/announcements/create.blade.php

```php
<?php
return view('admin.announcements.create', [
    'courses' => $courses, // Collection<Course>
    'defaults' => [
        'is_public' => false,
    ],
    'routes' => [
        'store' => route('admin.announcements.store'),
    ],
]);
```

### 14.3 admin/announcements/edit.blade.php

```php
<?php
return view('admin.announcements.edit', [
    'announcement' => $announcement->load('courses'),
    'courses' => $courses,
    'routes' => [
        'update' => route('admin.announcements.update', $announcement),
        'delete' => route('admin.announcements.destroy', $announcement),
    ],
]);
```

### 14.4 announcements/public/index.blade.php

```php
<?php
return view('announcements.public.index', [
    'announcements' => $announcements, // only is_public=true
    'routes' => [
        'show' => fn($a) => route('announcements.public.show', $a),
    ],
]);
```

### 14.5 announcements/student/index.blade.php

```php
<?php
return view('announcements.student.index', [
    'announcements' => $announcements, // public + enrolled-course announcements
    'routes' => [
        'show' => fn($a) => route('announcements.student.show', $a),
    ],
]);
```

### 14.6 Datetime / timezone conversions

Announcements do not have user-entered Jalali datetime fields.

- DB timestamps are fine (stored UTC via your general setup).
- Display in Blade: `verta($announcement->created_at->setTimezone($tz))->format('Y/m/d H:i')`.

## 15) Notifications — Blade Data Snippets

### 15.1 notifications/index.blade.php

```php
<?php
$tz = (string) (auth()->user()->timezone ?? config('app.timezone', 'UTC'));

return view('notifications.index', [
    'notifications' => $notifications, // paginator, each item has pivot->read_at
    'unreadCount' => $unreadCount,
    'tz' => $tz,
    'routes' => [
        'read' => fn($n) => route('notifications.read', $n),
        'unread' => fn($n) => route('notifications.unread', $n),
        'bulkRead' => route('notifications.bulkRead'),
    ],
]);
```

Datetime display (UTC → user timezone → Jalali):

```php
<?php
$createdJalali = verta($notification->created_at->copy()->setTimezone($tz))->format('Y/m/d H:i');

$pivotReadAt = $notification->pivot?->read_at;
$readAtJalali = $pivotReadAt
    ? verta(\Carbon\Carbon::parse($pivotReadAt)->setTimezone($tz))->format('Y/m/d H:i')
    : null;

$isUnread = $notification->pivot?->read_at === null;
```

Unread badge in navbar (optional). In any controller that renders layout, you can pass:

```php
<?php
$unreadCount = auth()->check()
    ? auth()->user()->notifications()->wherePivotNull('read_at')->count()
    : 0;
```

(Or later, share it globally via a view composer.)

## 16) Payments Routes Reference

```php
Route::middleware(['auth'])->group(function () {

    // Student
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/enrollments/{enrollment}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/review', [AdminPaymentController::class, 'review'])->name('payments.review');
    });
});
```

## 17) Auth & Profile — Blade Data Snippets

### 17.1 auth/register.blade.php

```php
<?php
return view('auth.register', [
    'defaults' => [
        'timezone' => 'UTC',
    ],
    'routes' => [
        'submit' => route('register.store'),
        'login' => route('login'),
    ],
]);
```

### 17.2 auth/login.blade.php

```php
<?php
return view('auth.login', [
    'routes' => [
        'submit' => route('login.store'),
        'register' => route('register'),
    ],
]);
```

### 17.3 auth/verify-email.blade.php

```php
<?php
return view('auth.verify-email', [
    'routes' => [
        'resend' => route('verification.send'),
        'logout' => route('logout'),
    ],
]);
```

### 17.4 auth/verify-phone.blade.php

```php
<?php
return view('auth.verify-phone', [
    'routes' => [
        'send' => route('phone.verify.send'),
        'check' => route('phone.verify.check'),
    ],
]);
```

### 17.5 myProfile.blade.php

```php
<?php
$tz = (string) (auth()->user()->timezone ?? 'UTC');

$birthdateJalali = auth()->user()->birthdate
    ? verta(auth()->user()->birthdate)->format('Y/m/d')
    : null;

return view('myProfile', [
    'user' => auth()->user(),
    'tz' => $tz,
    'birthdateJalali' => $birthdateJalali,
    'enums' => [
        'gender' => ['male', 'female', 'other'],
    ],
    'routes' => [
        'update' => route('profile.update'),
        'phoneVerify' => route('phone.verify.notice'),
        'emailVerify' => route('verification.notice'),
    ],
]);
```

## 18) Laravel 12 Note (No AuthServiceProvider)

That’s okay.

- Email verification works via `MustVerifyEmail` + routes/controllers above.
- Policies for other models will work if Laravel auto-discovers them (Laravel 11/12 does).
- For users admin CRUD, explicit `requireAdmin()` checks avoid provider-registration dependency.

## 19) Follow-up Needed

Your `DateTimeService` method names were assumed as:

- `jalaliDateToCarbonDate($jalaliDateString)`
- `jalaliToUtc($jalaliDatetimeString, $timezone)`

If your methods are named differently, paste `DateTimeService.php` (or your Verta conversion service) and I’ll adjust `UserService` and `PostService` accordingly.

Also, when you paste your SMS panel codes, we can wire rate limiting and attempt limits for OTP verification (recommended).

## 20) Password Reset — Blade Data Snippets

### 20.1 auth/forgot-password.blade.php

```php
<?php
return view('auth.forgot-password', [
    'routes' => [
        'submit' => route('password.email'),
        'login' => route('login'),
    ],
]);
```

### 20.2 auth/reset-password.blade.php

```php
<?php
return view('auth.reset-password', [
    'token' => $token,
    'email' => $email,
    'routes' => [
        'submit' => route('password.update'),
    ],
]);
```