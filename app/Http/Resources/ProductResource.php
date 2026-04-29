<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            // RELATIONS
            'category' => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'sub_category' => [
                'id'   => $this->subCategory?->id,
                'name' => $this->subCategory?->name,
            ],

            'brand' => [
                'id'   => $this->brand?->id,
                'name' => $this->brand?->name,
            ],

            // BASIC INFO

            'id'    => $this->id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            'sku'   => $this->sku,

            'short_description' => $this->short_description,
            'description'       => $this->description,

            // PRICING

            'price'          => (float) $this->price,
            'discount_price' => (float) $this->discount_price,
            'stock'          => (int) $this->stock,

            // IMAGES
            'thumbnail' => $this->thumbnail
                ? asset($this->thumbnail)
                : null,

            'gallery' => collect($this->gallery ?? [])
                ->map(fn($img) => asset($img))
                ->values(),

            // VARIANTS

            'variants' => $this->whenLoaded('variants', function () {
                return $this->variants->map(function ($variant) {
                    return [
                        'id'        => $variant->id,
                        'size'      => $variant->size,
                        'color'     => $variant->color,
                        'color_hex' => $variant->color_hex,
                        'price'     => (float) $variant->price,
                        'stock'     => (int) $variant->stock,
                    ];
                })->values();
            }),

            // FLAGS
            'is_featured' => (bool) $this->is_featured,
            'status'      => (bool) $this->status,
            // TAGS
            'tags' => collect($this->tags ?? [])->values(),


            'material'   => $this->material,
            'weight'     => $this->weight,
            'dimensions' => $this->dimensions,

            'created_at' => optional($this->created_at)->format('Y-m-d H:i:s'),


            'is_wishlisted' => (bool) ($this->is_wishlisted ?? false),
        ];
    }
}
