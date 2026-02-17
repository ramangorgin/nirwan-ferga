<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher'], true);
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:pending,waiting_list,confirmed,rejected,cancelled,completed'],
            'payment_status' => ['sometimes', 'in:unpaid,partial,paid,refunded'],
            'paid_amount' => ['sometimes', 'integer', 'min:0'],
            'final_score' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'certificate_issued' => ['sometimes', 'boolean'],
        ];
    }
}
