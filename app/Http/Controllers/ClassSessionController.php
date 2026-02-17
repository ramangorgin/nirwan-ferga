<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ClassSessionStoreRequest;
use App\Http\Requests\ClassSessionUpdateRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\ClassSession;
use App\Services\ClassSessions\ClassSessionService;

class ClassSessionController extends Controller
{

    public function __construct(
        protected ClassSessionService $classSessionService
    ) {}


    public function index(Request $request)
    {
        $query = ClassSession::query()
            ->with(['course:id,title,teacher_id'])
            ->latest();

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->integer('course_id'));
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->integer('student_id'));
        }

        // Important: if student, scope to own class sessions
        if (auth()->user()->role === 'student') {
            $query->where('student_id', auth()->id());
        }
        // Optional: if teacher, scope to own courses
        if (auth()->user()->role === 'teacher') {   
            $query->whereHas('course', fn ($q) => $q->where('teacher_id', auth()->id()));
        }   

        $classsessions = $query->paginate(15)->withQueryString();

        return view('classsessions.index', compact('classsessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('classsessions.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClassSessionStoreRequest $request)
    {
        // Validate the course exists and user is authorized
        $course = Course::findOrFail($request->input('course_id'));
        $this->authorize('create', [$course]);

        // Create the class session
        $classSession = ClassSession::create($request->validated());

        return redirect()
            ->route('class-sessions.show', $classSession)
            ->with('success', 'جلسه با موفقیت ایجاد شد.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classSession = ClassSession::with(['course:id,title,teacher_id'])->findOrFail($id);

        $this->authorize('view', $classSession);

        return view('classsessions.show', compact('classSession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $classSession = ClassSession::findOrFail($id);

        $this->authorize('update', $classSession);

        $courses = Course::select('id', 'title')->orderBy('title')->get();

        return view('classsessions.edit', compact('classSession', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassSessionUpdateRequest $request, string $id)
    {
        $classSession = ClassSession::findOrFail($id);

        // Authorize the user to update this session
        $this->authorize('update', $classSession);

        // Update the class session with validated data
        $classSession->update($request->validated());

        return redirect()
            ->route('class-sessions.show', $classSession)
            ->with('success', 'جلسه با موفقیت به‌روز شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $classSession = ClassSession::findOrFail($id);

        $this->authorize('delete', $classSession);

        $classSession->delete();

        return redirect()->route('class-sessions.index')->with('success', 'جلسه با موفقیت حذف شد.');
    }
}
