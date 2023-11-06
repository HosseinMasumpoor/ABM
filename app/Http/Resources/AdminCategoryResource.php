<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCategoryResource extends JsonResource
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
            'parent' => $this->parent,
            'subCategories' => $this->whenLoaded('subCategories', fn () => AdminCategoryResource::collection($this->subCategories->load('subCategories'))),
        ];
        // return parent::toArray($request);
    }
}
