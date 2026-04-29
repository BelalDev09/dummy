<?php

namespace App\Http\Requests\Cms\Home;

use Illuminate\Foundation\Http\FormRequest;

class HomePageCategorySectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'main_text' => 'required|string|max:255',


            'title' => 'nullable|array|max:3',
            'title.*' => 'nullable|string|max:255',

            'sub_title' => 'nullable|array|max:3',
            'sub_title.*' => 'nullable|string|max:255',

            'image' => 'nullable|array|max:3',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'button_text' => 'nullable|array|max:3',
            'button_text.*' => 'nullable|string|max:255',

            'button_link' => 'nullable|array|max:3',
            'button_link.*' => 'nullable|url|max:255',

            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'main_text.required' => 'Main text is required',

            'title.*.string' => 'Title must be a string',
            'sub_title.*.string' => 'Sub title must be a string',

            'image.*.image' => 'Each file must be an image',
            'image.*.mimes' => 'Only jpg, jpeg, png, webp allowed',

            'button_link.*.url' => 'Button link must be a valid URL',
        ];
    }
}
