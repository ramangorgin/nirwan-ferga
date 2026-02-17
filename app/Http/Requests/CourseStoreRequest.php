<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hekmatinasser\Verta\Verta;
use Illuminate\Validation\Rule;

class CourseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (!empty($data['start_date'])) {
            $data['start_date'] = Verta::parseFormat('Y/m/d', $data['start_date'])->toCarbon()->toDateString();
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = Verta::parseFormat('Y/m/d', $data['end_date'])->toCarbon()->toDateString();
        }

        if (!empty($data['registration_deadline'])) {
            $data['registration_deadline'] =
                Verta::parseFormat('Y/m/d', $data['registration_deadline'])
                    ->toCarbon()
                    ->setTime(23, 59, 59);
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:beginner,intermediate,advanced,free'],
            'teaching_in_kurdish' => ['required', 'boolean'],

            'capacity_min' => ['required', 'integer', 'min:1'],
            'capacity_max' => ['required', 'integer', 'gte:capacity_min'],


            'teacher_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'teacher'),
            ],

            'registration_deadline' => ['required', 'date'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],

            'days_of_week' => ['required', 'array'],
            'days_of_week.*' => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],

            'start_time' => ['required', 'date_format:H:i'],
            'session_duration' => ['required', 'integer', 'min:1'],
            'sessions_count' => ['required', 'integer', 'min:1'],

            'syllabus' => ['required', 'array'],

            'price' => ['required', 'integer', 'min:0'],

            'poster' => ['nullable', 'image'],
            'video' => ['nullable', 'file'],
        ];
    }
}
