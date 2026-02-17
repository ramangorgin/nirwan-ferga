<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use App\Services\Courses\CourseService;
use App\Models\User;
use App\Models\Enrollment;
use App\Http\Requests\EnrollmentStoreRequest;


class CourseController extends Controller
{

    public function __construct(
        protected CourseService $courseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $query = Course::query()
            ->with(['teacher:id,name'])
            ->withCount('enrollments');

        // Optional filters (safe defaults)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->integer('teacher_id'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where('title', 'like', "%{$q}%");
        }

        $courses = $query->latest()->paginate(15)->withQueryString();

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        
        // Get teachers for dropdown (only users who are teachers)
        $teachers = User::where('role', 'teacher')->select('id','name')->orderBy('name')->get();


        return view('courses.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request)
    {

        $this->authorize('create', Course::class);

        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('courses/posters', 'public');
        }

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('courses/videos', 'public');
        }

        $this->courseService->create($data, auth()->id());

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $course->load([
            'teacher:id,name',
            'classSessions' => fn ($q) => $q->orderBy('session_number'),
        ])->loadCount('enrollments');

        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        // Get teachers for dropdown (only users who are teachers)
        $teachers = User::where('role', 'teacher')
                        ->select('id', 'name')
                        ->orderBy('name')
                        ->get();

        return view('courses.edit', compact('teachers', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validated();

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('courses/posters', 'public');
        }

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('courses/videos', 'public');
        }

        $this->courseService->update($course, $data, auth()->id());

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // safer than hard delete for now
        $course->update([
            'is_active' => false,
            'status' => 'cancelled',
        ]);

        return redirect()
            ->route('courses.index')
            ->with('success', 'دوره با موفقیت لغو گشت.');
    }


    // Student self-enroll related method
    public function enroll(EnrollmentStoreRequest $request, Course $course)
    {
        $this->authorize('create', Enrollment::class);

        $data = $request->validated();

        if (! $course->isRegistrationOpen()) {
            abort(403);
        }

        if ($course->isFull()) {
            abort(422);
        }

        $exists = Enrollment::where('course_id', $course->id)
            ->where('student_id', auth()->id())
            ->exists();

        if ($exists) {
            abort(422);
        }

        $path = $request->file('payment_screenshot')
            ->store('enrollment_screenshots', 'public');

        Enrollment::create([
            'course_id' => $course->id,
            'student_id' => auth()->id(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'paid_amount' => 0,
            'payment_screenshot_path' => $path,
        ]);

        return back()->with('success', 'درخواست ثبت‌نام ارسال شد. لطفاً منتظر تایید باشید.');
    }

}
