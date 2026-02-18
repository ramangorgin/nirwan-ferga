<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Hekmatinaser\Verta\Verta;

class DiscountCodeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin'], true);
    }

    public function rules(): array
    {
        $discountCodeId =
            $this->route('discount_code')?->id
            ?? $this->route('discountCode')?->id
            ?? $this->input('id');

        return [
            'code' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('discount_codes', 'code')->ignore($discountCodeId),
            ],
            'percentage' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'user_id' => ['nullable', 'exists:users,id'],
            'expires_at' => ['nullable', 'date'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalize code
        if ($this->has('code') && is_string($this->input('code'))) {
            $this->merge([
                'code' => strtoupper(trim($this->input('code'))),
            ]);
        }

        // Convert Jalali expires_at (if provided as Y/m/d or Y/m/d H:i) to Gregorian datetime string
        if ($this->filled('expires_at') && is_string($this->input('expires_at'))) {
            $raw = trim($this->input('expires_at'));

            try {
                if (str_contains($raw, ':')) {
                    $v = Verta::parseFormat('Y/m/d H:i', $raw);
                    $this->merge(['expires_at' => $v->datetime()->format('Y-m-d H:i:s')]);
                } else {
                    $v = Verta::parseFormat('Y/m/d', $raw);
                    $this->merge(['expires_at' => $v->datetime()->endOfDay()->format('Y-m-d H:i:s')]);
                }
            } catch (\Throwable $e) {
            }
        }
    }
}
