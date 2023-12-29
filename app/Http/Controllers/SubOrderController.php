<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Sub_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubOrderController extends Controller
{
    use ResponseTrait;

    public function createSubOrder(Request $request)
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;
        if($role == 0)
        {
            $data = $request->all();
            $data['user_id'] = $userId;


            $medicine_id = $request['medicine_id'];

            $price = Medicine::query()->where('id' , $medicine_id)->value('price');
            $price *= $request['required_quantity'];

            $order_id = Order::query()
            ->where('user_id' , $userId)
            ->where('activate_num' , 1)
            ->value('id');

            $old_price = Order::query()->where('id' , $order_id)->value('price');

            Order::query()->where('id' , $order_id)->update([
                'price' => $old_price + $price
            ]);
           
            $data['order_id'] = $order_id;
            $data['total_price'] = $price;

            Sub_order::query()->create($data);

            return $this->SendResponse(null , 201 , "success");
        }
        return $this->SendError(401 , "you don't have the permission");
    }
}
