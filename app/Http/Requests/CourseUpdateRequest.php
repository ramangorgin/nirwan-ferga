<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Hekmatinasser\Verta\Verta;

class CourseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() &&
            in_array(auth()->user()->role, ['admin', 'teacher']);
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (!empty($data['start_date'])) {
            $data['start_date'] = Verta::parseFormat('Y/m/d', $data['start_date'])
                ->toCarbon()
                ->toDateString();
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = Verta::parseFormat('Y/m/d', $data['end_date'])
                ->toCarbon()
                ->toDateString();
        }

        if (!empty($data['registration_deadline'])) {
            $data['registration_deadline'] = Verta::parseFormat('Y/m/d', $data['registration_deadline'])
                ->toCarbon()
                ->setTime(23, 59, 59);
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'level' => ['sometimes', 'in:beginner,intermediate,advanced,free'],
            'teaching_in_kurdish' => ['sometimes', 'boolean'],

            'capacity_min' => ['sometimes', 'integer', 'min:1'],
            'capacity_max' => ['sometimes', 'integer', 'gte:capacity_min'],

            'teacher_id' => [
                'sometimes',
                Rule::exists('users', 'id')->where('role', 'teacher'),
            ],

            'registration_deadline' => ['sometimes', 'date'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],

            'days_of_week' => ['sometimes', 'array'],
            'days_of_week.*' => ['in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],

            'start_time' => ['sometimes'],
            'session_duration' => ['sometimes', 'integer', 'min:1'],
            'sessions_count' => ['sometimes', 'integer', 'min:1'],

            'syllabus' => ['sometimes', 'array'],

            'price' => ['sometimes', 'integer', 'min:0'],

            'poster' => ['nullable', 'image'],
            'video' => ['nullable', 'file'],
        ];
    }
}
