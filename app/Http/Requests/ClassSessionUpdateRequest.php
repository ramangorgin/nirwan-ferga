<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hekmatinasser\Verta\Verta;
use Illuminate\Validation\Validator;

class ClassSessionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin','teacher'], true);
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (!empty($data['session_date'])) {
            $data['session_date'] = Verta::parseFormat('Y/m/d', $data['session_date'])->toCarbon()->toDateString();
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required','exists:courses,id'],
            'title' => ['required','string','max:255'],
            'session_number' => ['required','integer','min:1'],
            'session_date' => ['required','date'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i'],
            'meeting_link' => ['nullable','url'],
            'status' => ['required','in:scheduled,held,cancelled,postponed'],
            'description' => ['nullable','string'],
            'has_materials' => ['boolean']
        ];
    }
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');

            if ($start && $end && strtotime($end) <= strtotime($start)) {
                $validator->errors()->add('end_time', 'ساعت پایان باید بعد از ساعت شروع باشد.');
            }
        });
    }
}
