<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionMaterialStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher'], true);
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'exists:class_sessions,id'],

            'file' => ['required', 'file', 'max:51200'], // 50MB

            'file_type' => ['required', 'in:video,audio,pdf,image,slides,other'],

            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'visibility' => ['required', 'in:public,students_only,hidden'],
        ];
    }
}
