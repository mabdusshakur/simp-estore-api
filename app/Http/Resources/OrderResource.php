<?php

namespace App\Http\Resources;

use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'user_id' => $this->user_id,
            'order_items' => OrderItemResource::collection(OrderItems::where('order_id', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
