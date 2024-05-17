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
            'id' => $this->id,
            'orderItems' => OrderItemResource::collection($this->orderItems),
            'total_discount' => $this->total_data["total_discount"],
            'total_items' => $this->total_data["total_items"],
            'total_preOffPrice' => $this->total_data["total_preOffPrice"],
            'total_price' => $this->total_price,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'code' => $this->code,
            'created_at' => $this->created_at,
        ];
    }
}
