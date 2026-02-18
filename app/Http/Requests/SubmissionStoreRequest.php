<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'answer_text' => ['nullable', 'string', 'max:10000'],
            'answer_json' => ['nullable', 'array'],
            'file' => ['nullable', 'file', 'max:51200'], // 50MB
        ];
    }

    public function messages(): array
    {
        return [
            'file.file' => 'فایل ارسالی معتبر نیست.',
            'file.max' => 'حجم فایل نباید بیشتر از ۵۰ مگابایت باشد.',
            'answer_text.max' => 'متن پاسخ خیلی طولانی است.',
        ];
    }
}
