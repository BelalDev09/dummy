<?php

namespace App\Http\Requests\Cms\Home;

use Illuminate\Foundation\Http\FormRequest;

class HomePageTopSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',

            'image'   => 'nullable',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'Title field is required.',
            'title.string'   => 'Title must be a valid string.',
            'title.max'      => 'Title cannot exceed 255 characters.',

            'sub_title.string' => 'Sub title must be a valid string.',
            'sub_title.max'    => 'Sub title cannot exceed 255 characters.',

            'button_text.string' => 'Button text must be a valid string.',
            'button_text.max'    => 'Button text cannot exceed 255 characters.',

            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'image.max'   => 'Image size must not exceed 2MB.',
        ];
    }
}
