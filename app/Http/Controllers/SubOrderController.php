<?php

namespace App\Http\Controllers;

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

            $order_id = Order::query()
            ->where('user_id' , $userId)
            ->where('activate_num' , 1)
            ->value('id');
           
            $data['order_id'] = $order_id;

            Sub_order::query()->create($data);

            return $this->SendResponse(null , 201 , "success");
        }
        return $this->SendError(401 , "you don't have the permission");
    }
}
