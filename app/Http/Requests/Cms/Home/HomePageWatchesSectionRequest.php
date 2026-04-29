<?php

namespace App\Http\Requests\Cms\Home;

use Illuminate\Foundation\Http\FormRequest;

class HomePageWatchesSectionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {

        return [
            'title' => 'nullable|string',
            'sub_title' => 'nullable|string',
            'button_text' => 'nullable|string',
            'link_url' => 'nullable|url',
        ];
    }
}
