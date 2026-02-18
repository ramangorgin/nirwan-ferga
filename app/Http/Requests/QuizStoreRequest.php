<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'quiz_type' => ['required', Rule::in(['normal_quiz', 'midterm', 'final_exam', 'placement_test'])],

            // Jalali datetime strings; converted in service using Verta
            'start_at' => ['required', 'string', 'max:32'],
            'end_at' => ['required', 'string', 'max:32'],

            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1000'],
            'attempt_limit' => ['nullable', 'integer', 'min:1', 'max:20'],

            'shuffle_questions' => ['nullable', 'boolean'],
            'shuffle_options' => ['nullable', 'boolean'],
            'auto_grade' => ['nullable', 'boolean'],
            'show_results_after_submissions' => ['nullable', 'boolean'],
            'show_correct_answers' => ['nullable', 'boolean'],

            'passing_score' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'syllabus_tags' => ['nullable', 'array'],
            'requirements_text' => ['nullable', 'string'],

            'visibility' => ['nullable', Rule::in(['draft', 'published', 'closed'])],
        ];
    }
}
