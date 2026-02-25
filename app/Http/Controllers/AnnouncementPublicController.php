<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Enrollment;
use Illuminate\View\View;

class AnnouncementPublicController extends Controller
{
    /**
     * Public announcements for home page (guest allowed).
     */
    public function index(): View
    {
        $announcements = Announcement::query()
            ->where('is_public', true)
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('announcements.public.index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Public show (guest allowed).
     * If you want to allow viewing non-public by enrolled students, use student controller.
     */
    public function show(Announcement $announcement): View
    {
        // If not public, hide from guests
        if (!$announcement->is_public) {
            abort(404);
        }

        $announcement->load(['author']);

        return view('announcements.public.show', [
            'announcement' => $announcement,
        ]);
    }

    /**
     * Student panel announcements:
     * - public announcements
     * - course-specific announcements for enrolled courses
     */
    public function my(): View
    {
        $user = auth()->user();
        if (!$user) abort(403);

        // enrolled courses
        $courseIds = Enrollment::query()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('course_id')
            ->toArray();

        $announcements = Announcement::query()
            ->with(['author', 'courses'])
            ->where(function ($q) use ($courseIds) {
                $q->where('is_public', true);

                if (!empty($courseIds)) {
                    $q->orWhereHas('courses', fn ($cq) => $cq->whereIn('courses.id', $courseIds));
                }
            })
            ->latest()
            ->paginate(20);

        return view('announcements.student.index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Student can view course-specific announcement if enrolled.
     */
    public function myShow(Announcement $announcement): View
    {
        $user = auth()->user();
        if (!$user) abort(403);

        if ($announcement->is_public) {
            $announcement->load('author');
            return view('announcements.student.show', ['announcement' => $announcement]);
        }

        $courseIds = Enrollment::query()
            ->where('student_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->pluck('course_id')
            ->toArray();

        $allowed = $announcement->courses()->whereIn('courses.id', $courseIds)->exists();
        if (!$allowed) abort(403);

        $announcement->load(['author', 'courses']);

        return view('announcements.student.show', [
            'announcement' => $announcement,
        ]);
    }
}