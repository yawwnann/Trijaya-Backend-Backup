<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'phone' => $this->resource->phone,
            'bio' => $this->resource->bio,
            'avatar' => $this->resource->avatar,
            'gender' => $this->resource->gender,
            'birth_date' => $this->resource->birth_date,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}