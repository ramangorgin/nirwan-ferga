<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['nullable','string','max:30','unique:users,phone'],
            'password' => ['required','string','min:6','confirmed'],

            'gender' => ['nullable','in:male,female,other'],
            'birthdate' => ['nullable','string','max:32'],
            'country' => ['nullable','string','max:255'],
            'city' => ['nullable','string','max:255'],
            'timezone' => ['nullable','string','max:64'],
        ];
    }
}