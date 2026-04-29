<?php

namespace App\Traits;

trait ProductMapTrait
{
    public function mapProduct($product)
    {
        return [
            'id'    => $product->id,
            'name'  => $product->name,
            'slug'  => $product->slug,

            'image' => $product->thumbnail
                ? asset($product->thumbnail)
                : ($product->image ? asset($product->image) : null),

            'price' => (float) $product->price,

            'is_wishlisted' => $product->is_wishlisted ?? false,

            'brand' => [
                'id'   => $product->brand?->id,
                'name' => $product->brand?->name,
            ],
        ];
    }
}
