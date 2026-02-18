<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceBulkUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'attendances' => ['required', 'array', 'min:1'],

            'attendances.*.status' => [
                'required',
                Rule::in(['present', 'absent', 'late', 'excused']),
            ],

            'attendances.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'attendances.required' => 'اطلاعات حضور و غیاب ارسال نشده است.',
            'attendances.array' => 'فرمت حضور و غیاب نامعتبر است.',
            'attendances.min' => 'حداقل باید حضور و غیاب یک دانش‌آموز ثبت شود.',
            'attendances.*.status.required' => 'وضعیت حضور/غیاب برای یک یا چند دانش‌آموز مشخص نشده است.',
            'attendances.*.status.in' => 'وضعیت حضور/غیاب نامعتبر است.',
            'attendances.*.note.string' => 'یادداشت حضور/غیاب باید متن باشد.',
            'attendances.*.note.max' => 'یادداشت حضور/غیاب نباید بیشتر از ۲۰۰۰ کاراکتر باشد.',
        ];
    }
}
