<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],

            'is_public' => ['nullable', 'boolean'],

            // If not public, admin selects courses
            'course_ids' => ['nullable', 'array', 'required_if:is_public,0'],
            'course_ids.*' => ['integer', 'exists:courses,id'],
        ];
    }
}