<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'content' => ['required', 'string'],

            'featured_image' => ['nullable', 'image', 'max:5120'], // 5MB
            'featured_image_alt' => ['nullable', 'string', 'max:255'],

            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:1000'],
            'seo_keywords' => ['nullable', 'string', 'max:2000'],
            'canonical_url' => ['nullable', 'string', 'max:2048'],

            'status' => ['nullable', Rule::in(['draft', 'published'])],
            // Optional manual publish time (Jalali string if you want): we convert in service
            'published_at' => ['nullable', 'string', 'max:50'],

            'is_indexable' => ['nullable', 'boolean'],
            'is_followable' => ['nullable', 'boolean'],
        ];
    }
}