<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
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
            'trade_name' => $this->trade_name,
            'scientific_name' => $this->scientific_name,
            'manufacture_company' => $this->manufacture_company,
            'photo' => $this->photo,
            'category' => $this->category->Category,
        ];
    }
}
