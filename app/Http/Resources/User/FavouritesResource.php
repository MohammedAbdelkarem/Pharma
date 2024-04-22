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
            'medicine_id' => $this->medicine_id,
            // 'medicines' => $this->favoriteMedicines->trade_name
            'admin_username' => $this->users->username,
        ];
    }
}
