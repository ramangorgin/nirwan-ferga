<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentStoreRequest;
use App\Http\Requests\AssignmentUpdateRequest;
use App\Http\Requests\AssignmentPersonalizationBulkUpsertRequest;
use App\Models\Assignment;
use App\Models\ClassSession;
use App\Services\Assignments\AssignmentService;
use Illuminate\Http\RedirectResponse;

class AssignmentController extends Controller
{
    public function __construct(
        protected AssignmentService $assignmentService
    ) {}

    public function store(ClassSession $class_session, AssignmentStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Assignment::class);

        $data = $request->validated();
        $data['session_id'] = $class_session->id;

        $assignment = $this->assignmentService->store($data, (int) auth()->id());

        return redirect()
            ->route('class-sessions.show', $assignment->session_id)
            ->with('success', 'تکلیف با موفقیت ایجاد شد.');
    }

    public function update(AssignmentUpdateRequest $request, Assignment $assignment): RedirectResponse
    {
        $this->authorize('update', $assignment);

        $updated = $this->assignmentService->update(
            assignment: $assignment,
            data: $request->validated(),
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('class-sessions.show', $updated->session_id)
            ->with('success', 'تکلیف با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        $this->authorize('delete', $assignment);

        $sessionId = $assignment->session_id;

        $this->assignmentService->delete($assignment);

        return redirect()
            ->route('class-sessions.show', $sessionId)
            ->with('success', 'تکلیف با موفقیت حذف شد.');
    }

    public function upsertPersonalizations(Assignment $assignment, AssignmentPersonalizationBulkUpsertRequest $request): RedirectResponse
    {
        $this->authorize('personalize', $assignment);

        $personalizations = $request->validated()['personalizations'];

        $this->assignmentService->upsertPersonalizations(
            assignment: $assignment,
            personalizations: $personalizations,
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('class-sessions.show', $assignment->session_id)
            ->with('success', 'تکالیف اختصاصی با موفقیت ثبت/به‌روزرسانی شدند.');
    }
}
