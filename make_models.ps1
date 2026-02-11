

$models = @(
    "Course",
    "Enrollment",
    "ClassSession",
    "Assignment",
    "AssignmentPersonalized",
    "Submission",
    "SessionMaterial",
    "Quiz",
    "QuizQuestion",
    "QuizSubmission",
    "QuizAnswer",
    "Attendance",
    "DiscountCode",
    "Notification",
    "NotificationUser",
    "Announcement",
    "AnnouncementCourse",
    "Post",
    "Ticket",
    "Message",
    "Payment"
)

foreach ($model in $models) {
    Write-Host "Creating model and factory for $model ..."
    php artisan make:model $model -f
}