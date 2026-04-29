<?php

namespace App\Http\Resources\API\V1\CMS\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class MenCollectionSectionResource extends JsonResource
{
    use \App\Traits\ProductMapTrait;

    public function toArray($request): array
    {
        $products = collect($this->products);

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'sub_title'  => $this->sub_title,
            'button_text' => $this->button_text,

            'categories' => $this->when($products->isNotEmpty(), function () use ($products) {
                return $products
                    ->groupBy('sub_category_id')
                    ->map(function ($items) {
                        $sub = $items->first()?->subCategory;
                        return [
                            'id'                => $sub?->id,
                            'sub_category_name' => $sub?->name,
                            'total_items'       => $items->count(),
                            'products'          => $items->values()
                                ->map(fn($p) => $this->mapProduct($p))
                                ->values(),
                        ];
                    })
                    ->values();
            }),

            'pagination' => $this->pagination ?? null,

            'status' => $this->status,
        ];
    }
}
