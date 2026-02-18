<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizSubmitRequest;
use App\Models\Quiz;
use App\Models\QuizSubmission;
use App\Services\Quizzes\QuizSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentQuizController extends Controller
{
    public function __construct(
        protected QuizSubmissionService $quizSubmissionService
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403);
        }

        $buckets = $this->quizSubmissionService->studentIndexBuckets((int) $user->id);

        return view('student.quizzes.index', $buckets);
    }

    public function show(Quiz $quiz): View
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403);
        }

        // Start or reuse pending attempt
        $submission = $this->quizSubmissionService->startOrGetPendingSubmission($quiz, (int) $user->id);

        // Load questions for rendering; shuffle is a UI concern, but you can shuffle here if you prefer
        $questions = $quiz->questions()->orderBy('order_index')->get();

        return view('student.quizzes.show', [
            'quiz' => $quiz,
            'submission' => $submission,
            'questions' => $questions,
        ]);
    }

    public function submit(Quiz $quiz, QuizSubmitRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403);
        }

        // Find the latest pending submission for this quiz/student
        $submission = QuizSubmission::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->whereNull('finished_at')
            ->orderByDesc('id')
            ->firstOrFail();

        $finished = $this->quizSubmissionService->submit(
            quiz: $quiz,
            submission: $submission,
            studentId: (int) $user->id,
            answers: $request->validated()['answers']
        );

        // If results are hidden, just show success and redirect to index
        if (!$quiz->show_results_after_submissions) {
            return redirect()
                ->route('student.quizzes.index')
                ->with('success', 'آزمون با موفقیت ثبت شد.');
        }

        // Otherwise, go to a results view (you can reuse show blade with finished submission if you want)
        return redirect()
            ->route('student.quizzes.result', [$quiz, 'submission' => $finished->id])
            ->with('success', 'آزمون با موفقیت ثبت شد.');
    }

    public function result(Quiz $quiz): View
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403);
        }

        // show latest finished attempt by default
        $submission = QuizSubmission::query()
            ->where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->whereNotNull('finished_at')
            ->orderByDesc('id')
            ->with('answers.question')
            ->firstOrFail();

        return view('student.quizzes.result', [
            'quiz' => $quiz,
            'submission' => $submission,
        ]);
    }
}
