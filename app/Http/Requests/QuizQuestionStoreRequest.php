<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizQuestionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'question_type' => ['required', Rule::in(['mcq', 'true_false', 'fill_blank', 'text'])],
            'question_text' => ['required', 'string', 'max:5000'],

            // options required for mcq; optional for others
            'options' => ['nullable', 'array', 'min:2'],
            'options.*' => ['nullable', 'string', 'max:500'],

            // correct_answer can contain multiple answers separated by newline/||/,/; etc (handled in grading)
            'correct_answer' => ['nullable', 'string', 'max:5000'],

            'score' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'order_index' => ['nullable', 'integer', 'min:1', 'max:100000'],
        ];
    }
}
