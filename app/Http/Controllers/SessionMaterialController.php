<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionMaterialStoreRequest;
use App\Http\Requests\SessionMaterialUpdateRequest;
use App\Models\SessionMaterial;
use App\Services\SessionMaterials\SessionMaterialService;
use Illuminate\Http\RedirectResponse;

class SessionMaterialController extends Controller
{
    public function __construct(
        protected SessionMaterialService $sessionMaterialService
    ) {}

    public function store(SessionMaterialStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', SessionMaterial::class);

        $data = $request->validated();
        $file = $request->file('file');

        $material = $this->sessionMaterialService->store(
            data: $data,
            file: $file,
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('class-sessions.show', $material->session_id)
            ->with('success', 'فایل آموزشی با موفقیت ثبت شد.');
    }

    public function update(SessionMaterialUpdateRequest $request, SessionMaterial $sessionMaterial): RedirectResponse
    {
        $this->authorize('update', $sessionMaterial);

        $data = $request->validated();
        $file = $request->file('file');

        $updated = $this->sessionMaterialService->update(
            material: $sessionMaterial,
            data: $data,
            file: $file,
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('class-sessions.show', $updated->session_id)
            ->with('success', 'فایل آموزشی با موفقیت به‌روزرسانی شد.');
    }


    public function destroy(SessionMaterial $sessionMaterial): RedirectResponse
    {
        $this->authorize('delete', $sessionMaterial);

        $sessionId = $sessionMaterial->session_id;

        $this->sessionMaterialService->delete(
            material: $sessionMaterial,
            actorUserId: (int) auth()->id()
        );

        return redirect()
            ->route('class-sessions.show', $sessionId)
            ->with('success', 'فایل آموزشی با موفقیت حذف شد.');
    }
}
