<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizSubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // answers[QUESTION_ID] = "student answer"
            'answers' => ['required', 'array', 'min:1'],
            'answers.*' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
