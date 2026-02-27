<?php

namespace App\Services\Users;

use App\Models\User;
use App\Services\DateTime\DateTimeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        protected DateTimeService $dateTimeService
    ) {}

    /**
     * Register a student user (production-fast).
     */
    public function registerStudent(array $data): User
    {
        return DB::transaction(function () use ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'], // hashed via mutator
                'role' => 'student',
                'status' => 'pending',

                'gender' => $data['gender'] ?? null,
                'birthdate' => !empty($data['birthdate'])
                    ? $this->dateTimeService->jalaliDateToGregorian($data['birthdate'])
                    : null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'timezone' => $data['timezone'] ?? 'Asia/Tehran',
            ]);
        });
    }

    public function adminCreate(array $data, ?UploadedFile $avatar): User
    {
        return DB::transaction(function () use ($data, $avatar) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'role' => $data['role'],
                'status' => $data['status'],
                'gender' => $data['gender'] ?? null,
                'birthdate' => !empty($data['birthdate'])
                    ? $this->dateTimeService->jalaliDateToGregorian($data['birthdate'])
                    : null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'timezone' => $data['timezone'] ?? 'UTC',
                'avatar' => null,
            ]);

            if ($avatar) {
                $path = $this->storeAvatar($user, $avatar);
                $user->update(['avatar' => $path]);
            }

            return $user;
        });
    }

    public function updateProfile(User $user, array $data, ?UploadedFile $avatar): User
    {
        return DB::transaction(function () use ($user, $data, $avatar) {

            foreach (['name','email','phone','gender','country','city','timezone'] as $field) {
                if (array_key_exists($field, $data)) {
                    $user->{$field} = $data[$field];
                }
            }

            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }

            if (array_key_exists('birthdate', $data)) {
                $user->birthdate = !empty($data['birthdate'])
                    ? $this->dateTimeService->jalaliDateToGregorian($data['birthdate'])
                    : null;
            }

            $user->save();

            if ($avatar) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $this->storeAvatar($user, $avatar);
                $user->update(['avatar' => $path]);
            }

            // If both verified, optionally activate automatically
            $this->autoActivateIfVerified($user);

            return $user->fresh();
        });
    }

    public function adminUpdate(User $user, array $data, ?UploadedFile $avatar): User
    {
        return DB::transaction(function () use ($user, $data, $avatar) {

            foreach (['name','email','phone','role','status','gender','country','city','timezone'] as $field) {
                if (array_key_exists($field, $data)) {
                    $user->{$field} = $data[$field];
                }
            }

            if (!empty($data['password'])) {
                $user->password = $data['password'];
            }

            if (array_key_exists('birthdate', $data)) {
                $user->birthdate = !empty($data['birthdate'])
                    ? $this->dateTimeService->jalaliDateToGregorian($data['birthdate'])
                    : null;
            }

            $user->save();

            if ($avatar) {
                if ($user->avatar) Storage::disk('public')->delete($user->avatar);
                $path = $this->storeAvatar($user, $avatar);
                $user->update(['avatar' => $path]);
            }

            return $user->fresh();
        });
    }

    protected function storeAvatar(User $user, UploadedFile $file): string
    {
        return $file->store("avatars/{$user->id}", 'public');
    }

    /**
     * If you want: automatically set status active when both email & phone are verified.
     */
    protected function autoActivateIfVerified(User $user): void
    {
        if ($user->status === 'pending'
            && $user->email_verified_at !== null
            && $user->phone_verified_at !== null
        ) {
            $user->update(['status' => 'active']);
        }
    }
}