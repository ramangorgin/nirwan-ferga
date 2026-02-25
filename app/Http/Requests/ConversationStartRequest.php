<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConversationStartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'exists:users,id'],
            'course_id' => ['nullable', 'exists:courses,id'],

            // First message is optional, but at least one of message/attachment should exist
            'message' => ['nullable', 'string', 'max:5000', 'required_without:attachment'],
            'attachment' => ['nullable', 'file', 'max:10240', 'required_without:message'], // 10MB
        ];
    }
}