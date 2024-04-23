<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouritesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'admin_id' => $this->admin_id,
            'admin_username' => $this->admin->username,
            'medicine_id' => $this->id,
            'medicine_trade_name' => $this->trade_name,
            'medicine_photo' => $this->photo,
        ];
    }
}
