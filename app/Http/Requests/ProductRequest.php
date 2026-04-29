<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'category_id'      => 'required|exists:categories,id',
            'sub_category_id'  => 'nullable|exists:sub_categories,id',
            'brand_id'         => 'nullable|exists:brands,id',
            'price'            => 'required|numeric|min:0',
            'discount_price'   => 'nullable|numeric|min:0|lt:price',
            'stock'            => 'nullable|integer|min:0',
            'thumbnail'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'short_description' => 'nullable|string',
            'description'      => 'nullable|string',
            'material'         => 'nullable|string|max:255',
            'weight'           => 'nullable|numeric|min:0',
            'dimensions'       => 'nullable|string|max:255',
            'tags'             => 'nullable|string',
            'is_featured'      => 'nullable|boolean',

            // Variants validation
            'variants'              => 'nullable|array',
            'variants.*.size'       => 'nullable|string|max:50',
            'variants.*.color'      => 'nullable|string|max:100',
            'variants.*.color_hex'  => 'nullable|string|max:7',
            'variants.*.price'      => 'nullable|numeric|min:0',
            'variants.*.stock'      => 'required_with:variants|integer|min:0',
        ];
    }
}
