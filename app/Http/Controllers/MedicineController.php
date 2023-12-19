<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Mailgun\Model\Message\SendResponse;

class MedicineController extends Controller
{
    use ResponseTrait;
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required'],
            'name' => ['required'],
            'scientific_name' => ['required'],
            'trade_name' => ['required'],
            'manufacture_company' => ['required'],
            'available_quantity' => ['required'],
            'Ed' => ['required'],
            'price' => ['required'],
            'photo' => ['required'],
        ]);
        Medicine::query()->create($data);
        return $this->SendResponse($data ,201,'medicine has been added successfully');
    }
    public function show(Request $request)
    {
        $data = Medicine::query()->where('category_id' , $request['id'])->first();
        return $this->SendResponse($data , 201 , 'this category products has been retrieved successfully');
    }
}
