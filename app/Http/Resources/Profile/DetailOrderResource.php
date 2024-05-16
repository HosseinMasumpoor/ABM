<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\AddressResource;
use App\Http\Resources\Profile\OrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'orderItems' => DetailOrderItemResource::collection($this->orderItems),
            'address' => new AddressResource($this->address),
            'total_price' => $this->total_price,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'code' => $this->code,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
