<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\Users\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function edit(): View
    {
        $user = auth()->user();

        return view('myProfile', [
            'user' => $user,
            'enums' => [
                'gender' => ['male','female','other'],
                'status' => ['active','deactive','ban','suspended','pending'],
                'role' => ['admin','teacher','student'],
            ],
            'tz' => (string) ($user->timezone ?? 'UTC'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $this->userService->updateProfile(
            user: $user,
            data: $request->validated(),
            avatar: $request->file('avatar')
        );

        return back()->with('success', 'پروفایل شما با موفقیت به‌روزرسانی شد.');
    }
}