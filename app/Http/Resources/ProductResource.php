<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "rate" => $this->rate,
            "name" => $this->name,
            "category" => new CategoryResource($this->category->load('parent')),
            "brand" => new BrandResource($this->brand),
            "sizes" => SizeResource::collection($this->sizes),
            "attributes" => AttributeResource::collection($this->attributes),
            "images" => ImageResource::collection($this->images),
            "image" => $this->image,
            "price" => $this->price,
            "offPrice" => $this->offPrice,
            "offPercent" => $this->offPercent,
            "color" => $this->color,
            "colorCode" => $this->colorCode,
            // 'comments' => $this->comments()->where('approved', true)->paginate(2),
            'comments' => new CommentResourceCollection($this->comments()->where('approved', true)->paginate()->load('user')),
            "created_at" => $this->created_at

        ];
    }
}
