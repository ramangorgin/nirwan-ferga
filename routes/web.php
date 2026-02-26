<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\ClassSessionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\SessionMaterialController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\SubmissionController;

use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\StudentQuizController;

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketMessageController;

use App\Http\Controllers\AdminAnnouncementController;
use App\Http\Controllers\AnnouncementPublicController;

use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\PostPublicController;

use App\Http\Controllers\NotificationController;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminPaymentController;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PhoneVerificationController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminUserController;

use App\Http\Controllers\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/ui', fn() => view('ui.playground'));

Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Public (Guest allowed)
|--------------------------------------------------------------------------
*/

// Public announcements
Route::get('/announcements', [AnnouncementPublicController::class, 'index'])
    ->name('announcements.public.index');

Route::get('/announcements/{announcement}', [AnnouncementPublicController::class, 'show'])
    ->name('announcements.public.show');

// Public blog
Route::get('/blog', [PostPublicController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostPublicController::class, 'show'])->name('posts.show');

/*
|--------------------------------------------------------------------------
| Auth (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
        ->name('password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'update'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Email verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // Phone verification (OTP)
    Route::get('/phone/verify', [PhoneVerificationController::class, 'notice'])->name('phone.verify.notice');
    Route::post('/phone/verify/send', [PhoneVerificationController::class, 'sendCode'])->name('phone.verify.send');
    Route::post('/phone/verify/check', [PhoneVerificationController::class, 'verifyCode'])->name('phone.verify.check');

    // Profile
    Route::get('/my/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/my/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Courses / Sessions / Enrollments
    |--------------------------------------------------------------------------
    */
    Route::resource('courses', CourseController::class);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

    Route::resource('class-sessions', ClassSessionController::class);

    Route::resource('enrollments', EnrollmentController::class);
    Route::get('enrollments/manual/create', [EnrollmentController::class, 'createManual'])->name('enrollments.manual.create');
    Route::post('enrollments/manual', [EnrollmentController::class, 'storeManual'])->name('enrollments.manual.store');

    /*
    |--------------------------------------------------------------------------
    | Discount Codes
    |--------------------------------------------------------------------------
    */
    Route::post('discount-codes/validate', [DiscountCodeController::class, 'validateCode'])
        ->name('discount-codes.validate');

    Route::resource('discount-codes', DiscountCodeController::class)
        ->parameters(['discount-codes' => 'discount_code']);

    /*
    |--------------------------------------------------------------------------
    | Session Materials
    |--------------------------------------------------------------------------
    */
    Route::post('session-materials', [SessionMaterialController::class, 'store'])
        ->name('session-materials.store');

    Route::patch('session-materials/{sessionMaterial}', [SessionMaterialController::class, 'update'])
        ->name('session-materials.update');

    Route::delete('session-materials/{sessionMaterial}', [SessionMaterialController::class, 'destroy'])
        ->name('session-materials.destroy');

    /*
    |--------------------------------------------------------------------------
    | Attendances
    |--------------------------------------------------------------------------
    */
    Route::post('class-sessions/{class_session}/attendances', [AttendanceController::class, 'upsert'])
        ->name('class-sessions.attendances.upsert');

    /*
    |--------------------------------------------------------------------------
    | Assignments / Submissions
    |--------------------------------------------------------------------------
    */
    Route::post('class-sessions/{class_session}/assignments', [AssignmentController::class, 'store'])
        ->name('class-sessions.assignments.store');

    Route::patch('assignments/{assignment}', [AssignmentController::class, 'update'])
        ->name('assignments.update');

    Route::delete('assignments/{assignment}', [AssignmentController::class, 'destroy'])
        ->name('assignments.destroy');

    Route::post('assignments/{assignment}/personalizations', [AssignmentController::class, 'upsertPersonalizations'])
        ->name('assignments.personalizations.upsert');

    Route::post('assignments/{assignment}/submissions', [SubmissionController::class, 'store'])
        ->name('assignments.submissions.store');

    Route::patch('submissions/{submission}/grade', [SubmissionController::class, 'grade'])
        ->name('submissions.grade');

    Route::get('class-sessions/{class_session}/submissions', [SubmissionController::class, 'indexSession'])
        ->name('class-sessions.submissions.index');

    /*
    |--------------------------------------------------------------------------
    | Student announcements panel
    |--------------------------------------------------------------------------
    */
    Route::get('/my/announcements', [AnnouncementPublicController::class, 'my'])
        ->name('announcements.student.index');

    Route::get('/my/announcements/{announcement}', [AnnouncementPublicController::class, 'myShow'])
        ->name('announcements.student.show');

    /*
    |--------------------------------------------------------------------------
    | Conversations (DM)
    |--------------------------------------------------------------------------
    */
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');

    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])
        ->name('conversations.messages.store');

    Route::post('/conversations/{conversation}/read', [MessageController::class, 'markRead'])
        ->name('conversations.read');

    /*
    |--------------------------------------------------------------------------
    | Tickets
    |--------------------------------------------------------------------------
    */
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

    Route::post('/tickets/{ticket}/messages', [TicketMessageController::class, 'store'])
        ->name('tickets.messages.store');

    Route::patch('/tickets/{ticket}/admin-update', [TicketController::class, 'adminUpdate'])
        ->name('tickets.adminUpdate');

    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])
        ->name('tickets.close');

    /*
    |--------------------------------------------------------------------------
    | Notifications UI
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/unread', [NotificationController::class, 'markUnread'])->name('notifications.unread');
    Route::post('/notifications/bulk-read', [NotificationController::class, 'bulkRead'])->name('notifications.bulkRead');

    /*
    |--------------------------------------------------------------------------
    | Payments (student)
    |--------------------------------------------------------------------------
    */
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/enrollments/{enrollment}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    /*
    |--------------------------------------------------------------------------
    | Quizzes (student)
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('quizzes', [StudentQuizController::class, 'index'])->name('quizzes.index');
        Route::get('quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('quizzes.show');
        Route::post('quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('quizzes/{quiz}/result', [StudentQuizController::class, 'result'])->name('quizzes.result');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Panel (auth required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Users
    Route::resource('users', AdminUserController::class);

    // Announcements
    Route::resource('announcements', AdminAnnouncementController::class);

    // Posts
    Route::resource('posts', AdminPostController::class);

    // Payments (admin) - IMPORTANT: unique route names
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/review', [AdminPaymentController::class, 'review'])->name('payments.review');

    // Quizzes (admin/teacher CRUD)
    Route::resource('quizzes', QuizController::class);

    Route::post('quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])
        ->name('quizzes.questions.store');

    Route::patch('quiz-questions/{question}', [QuizQuestionController::class, 'update'])
        ->name('quiz-questions.update');

    Route::delete('quiz-questions/{question}', [QuizQuestionController::class, 'destroy'])
        ->name('quiz-questions.destroy');
});