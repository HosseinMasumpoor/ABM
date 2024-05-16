<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_image' => $this->product->image,
            'size' => $this->size,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'created_at' => $this->created_at
        ];
    }
}