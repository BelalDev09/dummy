<?php

namespace App\Http\Resources;

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
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,

            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,

            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'notes' => $this->notes,

            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'avatar' => $this->user->avatar ?? null,
                'email' => $this->user->email ?? null,
            ],

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
