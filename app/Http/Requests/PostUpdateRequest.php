<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'excerpt' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'content' => ['sometimes', 'string'],

            'featured_image' => ['sometimes', 'nullable', 'image', 'max:5120'],
            'featured_image_alt' => ['sometimes', 'nullable', 'string', 'max:255'],

            'seo_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'seo_description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'seo_keywords' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'canonical_url' => ['sometimes', 'nullable', 'string', 'max:2048'],

            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'published_at' => ['sometimes', 'nullable', 'string', 'max:50'],

            'is_indexable' => ['sometimes', 'boolean'],
            'is_followable' => ['sometimes', 'boolean'],
        ];
    }
}