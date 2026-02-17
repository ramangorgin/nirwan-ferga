<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentManualStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin','teacher'], true);
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'student_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('users', 'id')->where('role', 'student'),
            ],
            'paid_amount' => ['nullable', 'integer', 'min:0'], 
        ];
    }

}
