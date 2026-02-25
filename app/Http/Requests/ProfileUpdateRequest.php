<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','string','max:255'],
            'email' => ['sometimes','email','max:255','unique:users,email,' . auth()->id()],
            'phone' => ['sometimes','nullable','string','max:30','unique:users,phone,' . auth()->id()],

            'avatar' => ['sometimes','nullable','image','max:5120'], // 5MB

            'gender' => ['sometimes','nullable','in:male,female,other'],
            'birthdate' => ['sometimes','nullable','string','max:32'], // Jalali string -> convert
            'country' => ['sometimes','nullable','string','max:255'],
            'city' => ['sometimes','nullable','string','max:255'],
            'timezone' => ['sometimes','string','max:64'],

            'password' => ['sometimes','nullable','string','min:6','confirmed'],
        ];
    }
}