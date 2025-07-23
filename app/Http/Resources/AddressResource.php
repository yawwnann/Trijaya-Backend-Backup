<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'label' => $this->resource->label,
            'recipient_name' => $this->resource->recipient_name,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'province' => $this->resource->province,
            'city' => $this->resource->city,
            'district' => $this->resource->district,
            'postal_code' => $this->resource->postal_code,
            'is_default' => (bool) $this->resource->is_default,
            'notes' => $this->resource->notes,
            'regency_id' => $this->resource->regency_id,
            'full_address' => $this->resource->full_address,
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}