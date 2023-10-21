<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "slug" => $this->slug,
            "name" => $this->name,
            "image" => $this->image,
            "price" => $this->price,
            "offPrice" => $this->offPrice,
            "offPercent" => $this->offPercent,
            "category" => $this->category,
            "created_at" => $this->created_at
        ];
    }
}
