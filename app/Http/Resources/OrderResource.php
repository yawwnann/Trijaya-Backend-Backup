<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\UserResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'address' => new AddressResource($this->whenLoaded('address')),
            'order_number' => $this->resource->order_number,
            'total_amount' => (float) $this->resource->total_amount,
            'shipping_cost' => (float) $this->resource->shipping_cost,
            'shipping_service' => $this->resource->shipping_service,
            'shipping_courier' => $this->resource->shipping_courier,
            'tax' => (float) $this->resource->tax,
            'discount' => (float) $this->resource->discount,
            'grand_total' => (float) $this->resource->grand_total,
            'status' => $this->resource->status,
            'payment_status' => $this->resource->payment_status,
            'payment_method' => $this->resource->payment_method,
            'payment_token' => $this->resource->payment_token,
            'notes' => $this->resource->notes,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->resource->created_at?->toISOString(),
            'updated_at' => $this->resource->updated_at?->toISOString(),
        ];
    }
}