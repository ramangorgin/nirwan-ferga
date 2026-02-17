<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'student';
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'payment_screenshot' => ['required', 'image', 'max:5120'], // 5MB
        ];
    }
}