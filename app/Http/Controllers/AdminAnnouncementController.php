<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Models\Course;
use App\Services\Announcements\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminAnnouncementController extends Controller
{
    public function __construct(
        protected AnnouncementService $announcementService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Announcement::class);

        $user = auth()->user();

        $q = Announcement::query()->with(['author', 'courses'])->latest();

        // Teachers see only their announcements
        if ($user->role === 'teacher') {
            $q->where('author_id', $user->id);
        }

        $announcements = $q->paginate(20);

        return view('admin.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Announcement::class);

        // Admin/Teacher selects courses if is_public is false
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.announcements.create', [
            'courses' => $courses,
            'defaults' => [
                'is_public' => false,
            ],
        ]);
    }

    public function store(AnnouncementStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Announcement::class);

        $announcement = $this->announcementService->create(
            data: $request->validated(),
            authorId: (int) auth()->id()
        );

        return redirect()
            ->route('admin.announcements.edit', $announcement)
            ->with('success', 'اطلاعیه ایجاد شد.');
    }

    public function show(Announcement $announcement): View
    {
        $this->authorize('view', $announcement);

        $announcement->load(['author', 'courses']);

        return view('admin.announcements.show', [
            'announcement' => $announcement,
        ]);
    }

    public function edit(Announcement $announcement): View
    {
        $this->authorize('update', $announcement);

        $announcement->load('courses');
        $courses = Course::query()->orderBy('title')->get();

        return view('admin.announcements.edit', [
            'announcement' => $announcement,
            'courses' => $courses,
        ]);
    }

    public function update(AnnouncementUpdateRequest $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('update', $announcement);

        $updated = $this->announcementService->update($announcement, $request->validated());

        return redirect()
            ->route('admin.announcements.edit', $updated)
            ->with('success', 'اطلاعیه به‌روزرسانی شد.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->authorize('delete', $announcement);

        $this->announcementService->delete($announcement);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'اطلاعیه حذف شد.');
    }
}