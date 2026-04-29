<?php

namespace App\Http\Resources\API\V1\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'sub_total' => $this->sub_total,
            'total_amount' => $this->total_amount,
            'status' => $this->order_status,
            'payment_status' => $this->payment_status,
        ];
    }
}
