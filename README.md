# Kurdish Classroom CMS (Laravel 12)

A lightweight Laravel 12 CMS as website for managing Kurdish-language classrooms.  
It is designed for a single teacher/admin at first, but supports scaling to multiple teachers later.

This project focuses on reaching production fast while keeping the codebase structured and extensible.

---

## Features

### Classroom Management
- Courses
- Class sessions
- Enrollments (student registration to courses)
- Discount codes
- Session materials upload (files per session)
- Attendance tracking per session
- Assignments (including personalized assignments per student)
- Submissions with auto-grading + manual grading workflows
- Quizzes with questions, student submissions, and auto-grading

### Communication
- Direct Conversations (student ↔ teacher) with messages and attachments
- Ticketing system (support-style tickets) with ticket messages and attachments

### Content & Marketing
- Public announcements (visible to everyone, including guests)
- Course-only announcements (visible to enrolled students)
- Blog posts (SEO-friendly: slug, canonical URL, meta fields, index/follow flags)

### Payments
- Enrollment payment receipts (upload screenshot/receipt)
- Admin review workflow: approve/reject with admin note
- Notifications and optional SMS on important events

### Notifications
- Notification creation via a NotificationService
- Notification UI: list notifications + unread count + mark read/unread + bulk read

### Internationalization & Time
- Inputs are often entered in Jalali format in Blade and converted to Carbon/UTC in backend
- Datetimes are stored in UTC and displayed in user timezone
- Persian/Arabic digits normalization middleware converts input digits to English digits automatically

---

## Tech Stack
- Laravel 12
- Blade (frontend will be implemented after backend completion)
- MySQL/MariaDB (recommended)
- File storage: Laravel filesystem (`public` disk)
- Jalali datetime: `hekmatinasser/verta`
- Optional SMS provider integration via `SmsService`

---

## Core Domain Overview

### Roles
- `admin`: full management access
- `teacher`: manages educational content (courses/sessions/materials/assignments/quizzes)
- `student`: enrolls, submits assignments/quizzes, uses conversations/tickets

### Key Entities
- **Course** → contains **ClassSessions**
- **Enrollment** → connects **Student ↔ Course**
- **SessionMaterial** → uploaded materials per session
- **Attendance** → per session + per student
- **Assignment** → per session, optionally personalized per student via `assignment_personalizations`
- **Submission** → student responses to assignments + grading timestamps
- **Quiz** → belongs to a course, contains questions; students submit answers
- **Announcements** → public OR course-specific
- **Posts** → public blog with SEO fields
- **Conversations/Messages** → direct student ↔ teacher messaging
- **Tickets/TicketMessages** → support workflow
- **Payments** → enrollment receipt uploads + admin approval workflow
- **Notifications** → created by services and delivered to users

---

## Installation

### Requirements
- PHP 8.2+ (recommended)
- Composer
- Node.js (for frontend assets later)
- MySQL/MariaDB

### Steps
1. Clone repository:
   ```git clone <repo-url> && cd <repo-folder>```

2. Install dependencies:
    ```composer install```

3. Copy env and generate key:

    ```cp .env.example .env &&  php artisan key:generate```

4. Configure .env:

Database connection

Mail config (for email verification/password reset)

Cache driver (Redis recommended)

Filesystem disk

5. Run migrations:

``` php artisan migrate ```

6. Create storage symlink:

```php artisan storage:link```

7. Run locally:

```php artisan serve```

## Mail (Email Verification + Password Reset)

This project uses Laravel’s built-in email verification and password reset workflows.

Configure .env mail settings (SMTP / Mailgun / SES / etc):

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Kurdish Classroom"
SMS Verification

Phone verification uses one-time codes (OTP) sent via the SmsService.
OTP can be stored in Cache (recommended for production with Redis).

In production:

Use CACHE_DRIVER=redis

Add resend throttling + attempt limits (recommended)

## Timezone & Jalali Date Handling

General rule:

Store all datetimes in UTC in database

Convert input Jalali datetime strings to UTC before saving

Display datetimes in user timezone and Jalali format in Blade via Verta

## Roadmap

Implement Blade views for admin panel and student panel

## License

Private project.
