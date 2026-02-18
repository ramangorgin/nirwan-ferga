<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizQuestionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'question_type' => ['sometimes', Rule::in(['mcq', 'true_false', 'fill_blank', 'text'])],
            'question_text' => ['sometimes', 'string', 'max:5000'],
            'options' => ['sometimes', 'nullable', 'array', 'min:2'],
            'options.*' => ['nullable', 'string', 'max:500'],
            'correct_answer' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'score' => ['sometimes', 'integer', 'min:1', 'max:1000'],
            'order_index' => ['sometimes', 'integer', 'min:1', 'max:100000'],
        ];
    }
}
