<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionMaterialUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher'], true);
    }

    public function rules(): array
    {
        return [
            'session_id' => ['sometimes', 'exists:class_sessions,id'],

            'file' => ['sometimes', 'file', 'max:51200'], // 50MB

            'file_type' => ['sometimes', 'required_with:file', 'in:video,audio,pdf,image,slides,other'],

            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],

            'visibility' => ['sometimes', 'in:public,students_only,hidden'],
        ];
    }
}
