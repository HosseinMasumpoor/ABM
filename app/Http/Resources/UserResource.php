<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => 11,
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "created_at" => $this->created_at,
            "otp_code" => null,
            "otp_expires_at" => $this->otp_expires_at,
            "is_active" => $this->is_active,
            "is_admin" => $this->is_admin,
            "role" => $this->role
        ];
        // return parent::toArray($request);
    }
}
