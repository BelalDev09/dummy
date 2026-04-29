<?php

namespace App\Http\Resources\API\V1\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id ?? null,
                'name' => $this->product->name ?? null,
                'sku' => $this->product->sku ?? null,
            ],
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total' => $this->price * $this->quantity,
        ];
    }
}
