<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mailgun\Model\Message\SendResponse;

class MedicineController extends Controller
{
    use ResponseTrait;
    public function store(Request $request)
    {
        
        // Get the authenticated user
        $user = Auth::user();

        // Get the token details from the request
        $token = $request->bearerToken();

        // Access other user details
        // $userId = $token['id'];
        $role = "owner";

    // Process the token and user details as needed

        if($role == 'owner')
        {
            // $data['user_id'] = $userId;
            $data = $request->validate([
                'price' => ['required'],
                'scientific_name' => ['required'],
                'trade_name' => ['required'],
                'manufacture_company' => ['required'],
                'available_quantity' => ['required'],
                'Ed' => ['required'],
                'name' => ['required'],
                'photo' => ['required'],
                'category_id' => ['required'],
                'user_id' =>  ['required']
            ]);
            $data['info'] = $user;


            Medicine::query()->create($data);
            return $this->SendResponse($data ,201,'medicine has been added successfully');
        }
    
    }
    public function show(Request $request)
    {
        $data = Medicine::query()->where('category_id' , $request['id'])->first();
        return $this->SendResponse($data , 201 , 'this category products has been retrieved successfully');
    }
}
