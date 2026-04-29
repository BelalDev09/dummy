<?php

namespace App\Http\Resources\API\V1\CMS\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class WatchesCollectionSectionResource extends JsonResource
{
    use \App\Traits\ProductMapTrait;

    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title ?? 'Watches',
            'sub_title'  => $this->sub_title,
            'button_text' => $this->button_text,

            'products'   => collect($this->products)
                ->map(fn($p) => $this->mapProduct($p))
                ->values(),

            'pagination' => $this->pagination ?? null,

            'status' => $this->status,
        ];
    }
}
