<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'course_id' => ['sometimes', 'exists:courses,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'quiz_type' => ['sometimes', Rule::in(['normal_quiz', 'midterm', 'final_exam', 'placement_test'])],

            // Jalali datetime strings; optional on update
            'start_at' => ['sometimes', 'string', 'max:32'],
            'end_at' => ['sometimes', 'string', 'max:32'],

            'duration_minutes' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'attempt_limit' => ['sometimes', 'integer', 'min:1', 'max:20'],

            'shuffle_questions' => ['sometimes', 'boolean'],
            'shuffle_options' => ['sometimes', 'boolean'],
            'auto_grade' => ['sometimes', 'boolean'],
            'show_results_after_submissions' => ['sometimes', 'boolean'],
            'show_correct_answers' => ['sometimes', 'boolean'],

            'passing_score' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100000'],
            'syllabus_tags' => ['sometimes', 'nullable', 'array'],
            'requirements_text' => ['sometimes', 'nullable', 'string'],

            'visibility' => ['sometimes', Rule::in(['draft', 'published', 'closed'])],
        ];
    }
}
