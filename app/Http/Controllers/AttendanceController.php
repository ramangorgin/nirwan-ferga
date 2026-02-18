<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceBulkUpsertRequest;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Services\Attendances\AttendanceService;
use Illuminate\Http\RedirectResponse;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function upsert(ClassSession $classSession, AttendanceBulkUpsertRequest $request): RedirectResponse
    {
        $this->authorize('upsert', [Attendance::class, $classSession]);

        $data = $request->validated();

        $attendances = $data['attendances'];

        $this->attendanceService->upsertBulk($classSession, $attendances);

        return redirect()
            ->route('class-sessions.show', $classSession)
            ->with('success', 'حضور و غیاب با موفقیت ثبت شد.');
    }
}
