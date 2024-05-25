<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\ProductCardResource;
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
            'product' => [
                'id' => $this->product->id ?? null,
                'slug' => $this->product->slug ?? null,
                'image' => $this->product->image ?? null,
                'brand' => $this->product->brand ?? null,
                'name' => $this->product_name,
                'price' => $this->product_price,
                'offPrice' => $this->product_offPrice,
                'color' => $this->product_color,
                'colorCode' => $this->product_colorCode,
            ],
            'size' => $this->size,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ];
    }
}
