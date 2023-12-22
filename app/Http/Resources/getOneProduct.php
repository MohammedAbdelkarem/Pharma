<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class getOneProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Scientific Name' => $this->scientific_name,
            'Trade Name' => $this->trade_name,
            'Manufacture Company' => $this->manufacture_company,
            'Available Quantity' => $this->available_quantity,
            'Expiered Date' => $this->Ed,
            'Price' => $this->price,
            'Photo' => $this->photo,
            //'Category' => $this->Category,
        ];
    }
}
