<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUserStoreRequest;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    protected function requireAdmin(): void
    {
        if (!auth()->user()?->isAdmin()) abort(403);
    }

    public function index(): View
    {
        $this->requireAdmin();

        $users = User::query()->latest()->paginate(30);

        return view('admin.users.index', [
            'users' => $users,
            'enums' => [
                'role' => ['admin','teacher','student'],
                'status' => ['active','deactive','ban','suspended','pending'],
            ],
        ]);
    }

    public function create(): View
    {
        $this->requireAdmin();

        return view('admin.users.create', [
            'enums' => [
                'role' => ['admin','teacher','student'],
                'status' => ['active','deactive','ban','suspended','pending'],
                'gender' => ['male','female','other'],
            ],
            'defaults' => [
                'role' => 'student',
                'status' => 'pending',
                'timezone' => 'UTC',
            ],
        ]);
    }

    public function store(AdminUserStoreRequest $request): RedirectResponse
    {
        $this->requireAdmin();

        $user = $this->userService->adminCreate($request->validated(), $request->file('avatar'));

        return redirect()->route('admin.users.edit', $user)->with('success', 'کاربر ایجاد شد.');
    }

    public function show(User $user): View
    {
        $this->requireAdmin();

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): View
    {
        $this->requireAdmin();

        return view('admin.users.edit', [
            'user' => $user,
            'enums' => [
                'role' => ['admin','teacher','student'],
                'status' => ['active','deactive','ban','suspended','pending'],
                'gender' => ['male','female','other'],
            ],
        ]);
    }

    public function update(AdminUserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->requireAdmin();

        $updated = $this->userService->adminUpdate($user, $request->validated(), $request->file('avatar'));

        return redirect()->route('admin.users.edit', $updated)->with('success', 'کاربر به‌روز شد.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->requireAdmin();

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'کاربر حذف شد.');
    }
}