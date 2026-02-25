<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'amount' => ['required', 'integer', 'min:1'],

            // Receipt image or PDF (allow both)
            'screenshot' => ['required', 'file', 'max:10240'], // 10MB
        ];
    }
}