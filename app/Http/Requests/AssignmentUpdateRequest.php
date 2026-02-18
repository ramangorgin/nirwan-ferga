<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],

            'type' => ['sometimes', Rule::in(['text', 'mcq', 'fill_blank', 'translation', 'file'])],
            'correct_answer' => ['sometimes', 'nullable', 'string'],
            'options' => ['sometimes', 'nullable', 'array'],

            'score' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'deadline' => ['sometimes', 'date'],

            'allow_late' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'closed'])],
        ];
    }
}
