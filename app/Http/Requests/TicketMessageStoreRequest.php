<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketMessageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'message' => ['required_without:attachment', 'nullable', 'string', 'max:10000'],
            'attachment' => ['required_without:message', 'nullable', 'file', 'max:10240'], // 10MB
        ];
    }
}