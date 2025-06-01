<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'data' => [
                'price' => $this->price,
                'discount' => $this->discount,
                'final_price' => $this->final_price,
                'payment_method' => $this->payment_method,
                'shipping_address' => $this->shipping_address,
                'notes' => $this->notes,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'items' => OrderItemResource::collection($this->items),
            ],
        ];
    }
}
