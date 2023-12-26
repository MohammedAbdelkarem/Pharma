<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use ResponseTrait;
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;
        if($role == 0)
        {
            $old_order = Order::query()->where('user_id' , $userId)->get();
            if(!$old_order->isEmpty())
            {
                Order::query()->where('user_id' , $userId)->update([
                    'activate_num' => 0
                ]);
            }
    
            $order_data=[];
            $order_data['user_id'] = $userId;
            $order_data['order_status'] = 0;
            $order_data['payment_status'] = 0;
            $order_data['activate_num'] = 1;
            Order::query()->create($order_data);
        }
    }
    public function updatPaymentStatus(Request $request)
    {
        Order::query()->where('id' , $request['order_id'])->update([
            'payment_status' => $request['payment_status']
        ]);
        return $this->SendResponse(null , 201 , 'payment status has been updated successfully');
    }
    public function updateOrderStatus(Request $request)
    {
        Order::query()->where('id' , $request['order_id'])->update([
            'order_status' => $request['order_status']
        ]);
        return $this->SendResponse(null , 201 , 'order status has been updated successfully');
    }

}
