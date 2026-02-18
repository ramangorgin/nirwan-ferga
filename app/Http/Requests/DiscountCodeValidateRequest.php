<?php

namespace App\Http\Requests;

use App\Models\DiscountCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DiscountCodeValidateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // used internally in enrollment flow
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'exists:discount_codes,code'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code') && is_string($this->input('code'))) {
            $this->merge([
                'code' => strtoupper(trim($this->input('code'))),
            ]);
        }
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->fails()) {
                return;
            }

            $user = $this->user();
            if (!$user) {
                $validator->errors()->add('code', 'برای اعتبارسنجی کد تخفیف باید وارد سیستم شوید.');
                return;
            }

            $code = $this->input('code');

            /** @var DiscountCode|null $discountCode */
            $discountCode = DiscountCode::query()
                ->where('code', $code)
                ->first();

            if (!$discountCode) {
                // exists rule should catch this, but keep it safe
                $validator->errors()->add('code', 'کد تخفیف یافت نشد.');
                return;
            }

            // Single source of truth: use the model helper
            if ($discountCode->canBeUsedBy($user)) {
                return;
            }

            // Provide a specific error message based on model helpers
            if (!$discountCode->isAvailable()) {
                if ($discountCode->isExpired()) {
                    $validator->errors()->add('code', 'این کد تخفیف منقضی شده است.');
                } else {
                    $validator->errors()->add('code', 'این کد تخفیف غیرفعال است.');
                }
                return;
            }

            if ($discountCode->isRestricted() && $discountCode->user_id !== $user->id) {
                $validator->errors()->add('code', 'این کد تخفیف برای شما قابل استفاده نیست.');
                return;
            }

            // Max uses reached (avoid extra queries unless needed)
            if (!$discountCode->hasUnlimitedUses()) {
                $remaining = $discountCode->remainingUses();
                if ($remaining !== null && $remaining <= 0) {
                    $validator->errors()->add('code', 'سقف استفاده از این کد تخفیف پر شده است.');
                    return;
                }
            }

            // Fallback
            $validator->errors()->add('code', 'این کد تخفیف قابل استفاده نیست.');
        });
    }
}
