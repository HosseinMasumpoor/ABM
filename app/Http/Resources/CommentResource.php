<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'user' => $this->is_anonymous ? null : $this->user->name,
            'rate' => $this->rate,
            'is_anonymous' => $this->is_anonymous,
            'text' => $this->text,
            'approved' => $this->approved,
            // 'product' => $this->whenLoaded('product', fn()=> new ProductCardResource($this->product)),
            'product' => $this->product,
            'time' => $this->created_at
        ];
    }
}
