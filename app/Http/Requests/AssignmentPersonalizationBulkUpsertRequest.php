<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignmentPersonalizationBulkUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'personalizations' => ['required', 'array', 'min:1'],

            'personalizations.*.custom_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'personalizations.*.custom_description' => ['sometimes', 'nullable', 'string'],

            'personalizations.*.custom_type' => ['sometimes', 'nullable', Rule::in(['text', 'mcq', 'fill_blank', 'translation', 'file'])],
            'personalizations.*.custom_options' => ['sometimes', 'nullable', 'array'],
            'personalizations.*.custom_correct_answer' => ['sometimes', 'nullable', 'string'],

            'personalizations.*.custom_deadline' => ['sometimes', 'nullable', 'date'],
            'personalizations.*.custom_score' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:1000'],
        ];
    }
}
