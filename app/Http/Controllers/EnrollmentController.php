<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentManualStoreRequest;
use App\Http\Requests\EnrollmentUpdateRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\Enrollments\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Enrollment::class);

        $query = Enrollment::query()
            ->with(['course:id,title,teacher_id', 'student:id,name'])
            ->latest();

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->integer('student_id'));
        }

        // Important: if student, scope to own enrollments
        if (auth()->user()->role === 'student') {
            $query->where('student_id', auth()->id());
        }

        // Optional: if teacher, scope to own courses
        if (auth()->user()->role === 'teacher') {
            $query->whereHas('course', fn ($q) => $q->where('teacher_id', auth()->id()));
        }

        $enrollments = $query->paginate(15)->withQueryString();

        return view('enrollments.index', compact('enrollments'));
    }

    // Admin/teacher form for manual enrollment
    public function createManual()
    {
        $this->authorize('create', Enrollment::class);

        $students = User::where('role', 'student')->select('id', 'name')->orderBy('name')->get();
        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('enrollments.create-manual', compact('students', 'courses'));
    }

    public function storeManual(EnrollmentManualStoreRequest $request)
    {
        $this->authorize('create', Enrollment::class);

        $data = $request->validated();
        $course = Course::findOrFail($data['course_id']);

        $this->enrollmentService->manualEnroll(
            course: $course,
            studentId: $data['student_id'],
            actorUserId: auth()->id(),
            paidAmount: $data['paid_amount'] ?? null
        );

        return redirect()->route('enrollments.index')->with('success', 'ثبت‌نام با موفقیت انجام شد.');
    }

    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);

        $enrollment->load([
            'course:id,title,teacher_id',
            'student:id,name',
            'discountCode',
        ]);

        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $enrollment->load(['course:id,title,teacher_id', 'student:id,name']);

        $statusOptions = ['pending','waiting_list','confirmed','rejected','cancelled','completed'];
        $paymentOptions = ['unpaid','partial','paid','refunded'];

        return view('enrollments.edit', compact('enrollment', 'statusOptions', 'paymentOptions'));
    }

    public function update(EnrollmentUpdateRequest $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);

        $data = $request->validated();

        $this->enrollmentService->verifyOrUpdate(
            enrollment: $enrollment,
            data: $data,
            actorUserId: auth()->id()
        );

        return redirect()->route('enrollments.show', $enrollment)->with('success', 'ثبت‌نام بروزرسانی شد.');
    }

    // treat destroy as cancel
    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('cancel', $enrollment);

        $this->enrollmentService->cancel($enrollment, auth()->id());

        return redirect()->route('enrollments.index')->with('success', 'ثبت‌نام لغو شد.');
    }
}
