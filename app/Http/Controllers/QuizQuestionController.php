<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizQuestionStoreRequest;
use App\Http\Requests\QuizQuestionUpdateRequest;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Services\Quizzes\QuizService;
use Illuminate\Http\RedirectResponse;

class QuizQuestionController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function store(Quiz $quiz, QuizQuestionStoreRequest $request): RedirectResponse
    {
        $this->authorize('update', $quiz);

        $this->quizService->addQuestion(
            quiz: $quiz,
            data: $request->validated(),
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('admin.quizzes.show', $quiz)
            ->with('success', 'سوال با موفقیت اضافه شد.');
    }

    public function update(QuizQuestionUpdateRequest $request, QuizQuestion $question): RedirectResponse
    {
        $this->authorize('update', $question);

        $this->quizService->updateQuestion($question, $request->validated());

        return redirect()
            ->route('admin.quizzes.show', $question->quiz_id)
            ->with('success', 'سوال با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(QuizQuestion $question): RedirectResponse
    {
        $this->authorize('delete', $question);

        $quizId = $question->quiz_id;

        $this->quizService->deleteQuestion($question);

        return redirect()
            ->route('admin.quizzes.show', $quizId)
            ->with('success', 'سوال با موفقیت حذف شد.');
    }
}
