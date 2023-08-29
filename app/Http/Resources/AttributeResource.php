<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'name' => $this->name,
            'value' => $this->value,
            'product' => $this->whenLoaded('product', fn()=> new ProductResource($this->product))
        ];
        // return parent::toArray($request);
    }
}
