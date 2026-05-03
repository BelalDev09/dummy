<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'description' => ['nullable', 'string'],
            'country' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Brand name is required.',
            'name.string' => 'Brand name must be valid text.',
            'name.max' => 'Brand name may not exceed 255 characters.',

            'logo.image' => 'Logo must be an image file.',
            'logo.mimes' => 'Logo must be jpg, jpeg, png, or webp format.',
            'logo.max' => 'Logo size may not exceed 2MB.',

            'image.image' => 'Image must be an image file.',
            'image.mimes' => 'Image must be jpg, jpeg, png, or webp format.',
            'image.max' => 'Image size may not exceed 2MB.',

            'banner.image' => 'Banner must be an image file.',
            'banner.mimes' => 'Banner must be jpg, jpeg, png, or webp format.',
            'banner.max' => 'Banner size may not exceed 4MB.',

            'description.string' => 'Description must be valid text.',

            'country.string' => 'Country must be valid text.',
            'country.max' => 'Country name may not exceed 255 characters.',

            'website.url' => 'Please enter a valid website URL.',
        ];
    }
}
