<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'scientific_name' => $this->scientific_name,  
            'trade_name' => $this->trade_name,  
            'manufacture_company' => $this->manufacture_company,  
            'available_quantity' => $this->available_quantity,  
            'Ed' => $this->Ed,  
            'price' => $this->price,  
            'photo' => $this->photo,  
            'category' => $this->category->Category,  
            'admin' => $this->admin->username,  
        ];
    }
}
