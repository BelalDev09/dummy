<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'quantity'  => $this->quantity,
            'price'     => (float) $this->price,
            'total'     => (float) ($this->price * $this->quantity),
            'status'    => $this->status,

            'created_at' => $this->created_at->format('Y-m-d H:i'),

            // USER
            'user' => [
                'id'    => $this->user?->id,
                'name'  => $this->user?->name,
                'email' => $this->user?->email,
                'avatar' => $this->user?->avatar
                    ? asset('storage/' . $this->user->avatar)
                    : asset('Backend/assets/images/default-user.png'),
            ],

            // PRODUCT
            'product' => [
                'id'    => $this->product?->id,
                'name'  => $this->product?->name,

                'brand' => $this->product?->brand?->name,
                'category' => $this->product?->category?->name,
                'sub_category' => $this->product?->subCategory?->name,

                'thumbnail' => $this->product?->thumbnail
                    ? asset('storage/' . $this->product->thumbnail)
                    : asset('Backend/assets/images/default-product.png'),

                // GALLERY FIX
                'gallery' => $this->formatGallery($this->product?->gallery),

                'description' => $this->product?->description,
            ],

            // VARIANT
            'variant' => [
                'id'   => $this->variant?->id,
                'name' => $this->variant?->name,
            ],
        ];
    }

    private function formatGallery($gallery)
    {
        if (!$gallery) return [];

        $images = is_array($gallery) ? $gallery : json_decode($gallery, true);

        if (!is_array($images)) return [];

        return array_map(function ($img) {
            return asset('storage/' . $img);
        }, $images);
    }
}
