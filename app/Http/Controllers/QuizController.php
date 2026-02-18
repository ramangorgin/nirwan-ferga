<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizStoreRequest;
use App\Http\Requests\QuizUpdateRequest;
use App\Models\Quiz;
use App\Services\Quizzes\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Quiz::class);

        $user = auth()->user();

        $q = Quiz::query()->with('course')->orderByDesc('id');

        // Teacher sees only their own course quizzes
        if ($user->role === 'teacher') {
            $q->whereHas('course', fn ($cq) => $cq->where('teacher_id', $user->id));
        }

        return view('admin.quizzes.index', [
            'quizzes' => $q->paginate(20),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Quiz::class);

        // In blade you will load courses list (teacher-limited if needed)
        return view('admin.quizzes.create');
    }

    public function store(QuizStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Quiz::class);

        $quiz = $this->quizService->store(
            data: $request->validated(),
            actorUserId: (int) auth()->id(),
            actorTz: (string) (auth()->user()->timezone ?? 'UTC')
        );

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'آزمون با موفقیت ایجاد شد.');
    }

    public function show(Quiz $quiz): View
    {
        $this->authorize('view', $quiz);

        return view('admin.quizzes.show', [
            'quiz' => $quiz->load('course', 'questions'),
        ]);
    }

    public function edit(Quiz $quiz): View
    {
        $this->authorize('update', $quiz);

        return view('admin.quizzes.edit', [
            'quiz' => $quiz->load('course'),
        ]);
    }

    public function update(QuizUpdateRequest $request, Quiz $quiz): RedirectResponse
    {
        $this->authorize('update', $quiz);

        $updated = $this->quizService->update(
            quiz: $quiz,
            data: $request->validated(),
            actorTz: (string) (auth()->user()->timezone ?? 'UTC')
        );

        return redirect()
            ->route('admin.quizzes.show', $updated)
            ->with('success', 'آزمون با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        $this->authorize('delete', $quiz);

        $this->quizService->delete($quiz);

        return redirect()
            ->route('admin.quizzes.index')
            ->with('success', 'آزمون با موفقیت حذف شد.');
    }
}
