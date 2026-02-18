<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionGradeRequest;
use App\Http\Requests\SubmissionStoreRequest;
use App\Models\Assignment;
use App\Models\ClassSession;
use App\Models\Submission;
use App\Services\Submissions\SubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService
    ) {}

    /**
     * دانشجو پاسخ تکلیف را ارسال می‌کند
     */
    public function store(Assignment $assignment, SubmissionStoreRequest $request): RedirectResponse
    {
        $this->authorize('submit', Submission::class);

        $submission = $this->submissionService->submit(
            assignment: $assignment,
            studentId: (int) auth()->id(),
            data: $request->validated(),
            file: $request->file('file')
        );

        return redirect()
            ->route('class-sessions.show', $submission->assignment->session_id)
            ->with('success', 'پاسخ تکلیف با موفقیت ثبت شد.');
    }

    /**
     * استاد/ادمین نمره‌دهی دستی انجام می‌دهد
     */
    public function grade(Submission $submission, SubmissionGradeRequest $request): RedirectResponse
    {
        $this->authorize('grade', $submission);

        $graded = $this->submissionService->gradeManually(
            submission: $submission,
            graderId: (int) auth()->id(),
            scoreObtained: (int) $request->validated()['score_obtained'],
            feedback: $request->validated()['feedback_text'] ?? null
        );

        return redirect()
            ->route('class-sessions.show', $graded->assignment->session_id)
            ->with('success', 'نمره و بازخورد با موفقیت ثبت شد.');
    }

    public function indexSession(ClassSession $class_session): View
    {
        $user = auth()->user();

        if ($user->role === 'teacher' && (int) $class_session->course?->teacher_id !== (int) $user->id) {
            abort(403);
        }

        if (!in_array($user->role, ['admin', 'teacher'], true)) {
            abort(403);
        }

        $submissionsByAssignment = $this->submissionService->listSessionSubmissions($class_session);

        return view('admin.class-sessions.submissions', [
            'session' => $class_session->load('course', 'assignments'),
            'submissionsByAssignment' => $submissionsByAssignment,
        ]);
    }
}
