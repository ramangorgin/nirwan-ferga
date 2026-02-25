<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],

            'priority' => ['nullable', Rule::in(['low', 'medium', 'high'])],

            // First message: require message or attachment
            'message' => ['nullable', 'string', 'max:10000', 'required_without:attachment'],
            'attachment' => ['nullable', 'file', 'max:10240', 'required_without:message'], // 10MB
        ];
    }
}