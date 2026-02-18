<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'exists:class_sessions,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'type' => ['required', Rule::in(['text', 'mcq', 'fill_blank', 'translation', 'file'])],

            'correct_answer' => ['nullable', 'string'],
            'options' => ['nullable', 'array'],

            'score' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'deadline' => ['required', 'date'],

            'allow_late' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'closed'])],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'جلسه مشخص نشده است.',
            'session_id.exists' => 'جلسه نامعتبر است.',
            'title.required' => 'عنوان تکلیف الزامی است.',
            'type.required' => 'نوع تکلیف الزامی است.',
            'type.in' => 'نوع تکلیف نامعتبر است.',
            'deadline.required' => 'ددلاین تکلیف الزامی است.',
            'deadline.date' => 'فرمت ددلاین نامعتبر است.',
        ];
    }
}
