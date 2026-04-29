<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('sub_category') ?? $this->route('sub_category_id');

        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories', 'name')->ignore($id),
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:5120',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category select is required।',
            'category_id.exists' => 'Valid category select is required।',

            'name.required' => 'Sub category name is required।',
            'name.unique' => 'Sub category name must be unique।',

            'image.image' => 'File must be an image।',
            'image.mimes' => 'Only jpg, jpeg, png, webp allowed।',
        ];
    }
}
