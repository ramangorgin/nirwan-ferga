<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationBulkReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'notification_ids' => ['required', 'array', 'min:1'],
            'notification_ids.*' => ['integer', 'exists:notifications,id'],
        ];
    }
}