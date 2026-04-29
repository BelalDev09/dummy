<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RelatedProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'price' => (float) $this->price,
            'thumbnail' => $this->thumbnail ? asset($this->thumbnail) : null,

            'brand' => [
                'id'   => $this->brand?->id,
                'name' => $this->brand?->name,
            ],
            'is_wishlisted' => (bool) ($this->is_wishlisted ?? false),
        ];
    }
}
