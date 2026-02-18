<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'score_obtained' => ['required', 'integer', 'min:0'],
            'feedback_text' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'score_obtained.required' => 'نمره الزامی است.',
            'score_obtained.integer' => 'نمره باید عدد باشد.',
            'score_obtained.min' => 'نمره نمی‌تواند منفی باشد.',
        ];
    }
}
