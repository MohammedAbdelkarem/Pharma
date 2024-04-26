<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'order_status' => $this->order_status,
            'payment_status' => $this->payment_status,
            'price' => $this->price,
            'user' => $this->user->username,
            'admin' => $this->admin->username,
        ];
    }
}
