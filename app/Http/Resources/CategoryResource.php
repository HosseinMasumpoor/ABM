<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'icon' => $this->icon,
            'parent_id' => $this->parent,
            'parent' => $this->whenLoaded('parent', fn () => new CategoryResource($this->parent->load('parent'))),
            'products' => $this->whenLoaded('products', fn () => ProductResource::collection($this->products)),
            'subCategories' => $this->whenLoaded('subCategories', fn () => CategoryResource::collection($this->subCategories->load('subCategories'))),
        ];
        // return parent::toArray($request);
    }
}
