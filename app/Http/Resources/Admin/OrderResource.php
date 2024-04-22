<?php

namespace App\Http\Resources\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
r
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
            'order_status' => $this->order_status,
            'payment_status' => $this->payment_status,
            'active_status' => $this->active_status,
            'price' => $this->price,
            'user' => $this->user->username,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d')
        ];
    }
}
