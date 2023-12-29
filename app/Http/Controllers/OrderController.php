<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\userResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\orderResource;
use App\Http\Resources\getCategoryResource;
use App\Models\Medicine;
use App\Models\Sub_order;

use function PHPUnit\Framework\isEmpty;

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
            if(!$old_order->isEmpty()) // check if the old order is exist, then if it is:change the avtive num to 0
            {
                $order_id = DB::table('orders') // get the old order id
                ->where('user_id' , $userId)
                ->where('activate_num' , 1)
                ->value('id');



                $sub_orders = Sub_order::query() // get the old order id
                ->where('order_id' , $order_id)
                ->select('medicine_id' ,  'required_quantity')
                ->get();


                foreach ($sub_orders as $item) {
                    $medicineId = $item->medicine_id;
                    $requiredQuantity = $item->required_quantity;

                    $oldQuantity = DB::table('medicines')
                    ->where('id', $medicineId)
                    ->value('available_quantity');
                    
                    $newQuantity = $oldQuantity - $requiredQuantity;
            
                    DB::table('medicines')
                        ->where('id', $medicineId)
                        ->update(['available_quantity' => $newQuantity]);
                }

                Order::query()
                ->where('user_id' , $userId)
                ->where('activate_num' , 1)
                ->update([
                    'activate_num' => 0
                ]);
            }
    
            $order_data=[];             //create the new empty order
            $order_data['user_id'] = $userId;
            $order_data['order_status'] = 0;
            $order_data['payment_status'] = 0;
            $order_data['activate_num'] = 1;
            $order_data['price'] = 0;
            Order::query()->create($order_data);
        }
        return $this->SendResponse(null , 201 , "success");
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
    public function getOrdersIdForOwner(Request $request) //gofo
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;

        if($role == 1)
        {
            
            $data1 = (DB::table('orders')
            ->where('order_status', '<', 2)
            ->where('activate_num', '=', 0)
            ->get());
            $user_id = [];
            $data2=[];
            foreach($data1 as $d)
            {
                $data2[] = (User::find($d->user_id));
            }
            if($data1->isEmpty())
            {
                return $this->SendError(201 , "no orders");
            }

            foreach ($data1 as &$order) {
                $order->username = null;
                foreach ($data2 as $user) {
                    if ($order->user_id == $user->id) {
                        $order->username = $user->username;
                        break;
                    }
                }
            }
            $data = [];
            $data['order'] = $data1;

            return $this->SendResponse($data , 201 , "success");
        }
        return $this->SendError(401 , "you are not the owner");
    }

    public function getOrdersIdForUser(Request $request) //gofu
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;

        if($role == 0)
        {
            $data = [];
            $data1 = (DB::table('orders') // submitted orders
            ->where('user_id',$userId)
            ->where('order_status', '<', 2)
            ->where('activate_num', '=', 0)
            ->select('id')
            ->get());
            $data2 = (DB::table('orders') // unsubmitted orders but has a sub orders(not the default empty order)
            ->where('user_id',$userId)
            ->where('activate_num', '=', 1)
            ->select('id')
            ->get());
            $order_id = DB::table('orders')
            ->where('user_id',$userId)
            ->where('activate_num', '=', 1)
            ->value('id');
            $sub_orders = (DB::table('sub_orders')->where('order_id' , $order_id)->get());
            if(!($data1->isEmpty()))
            {
                $data['submitted_orders'] = $data1;
            }
            if(!($sub_orders->isEmpty()))
            {
                $data['unsubmitted_orders'] = $data2;
            }
            if(empty($data))
            {
                return $this->SendError(401 , "no orders");
            }
            return $this->SendResponse($data , 201 , "success");

        }
        return $this->SendError(401 , "you are not the owner");
    }

    public function getOrdersDetails(Request $request) //godf
    {
        
            $data1 = (DB::table('orders')->where('id' , $request['id'])->get());
            $data2 = (DB::table('sub_orders')->where('order_id' , $request['id'])->get());


            $data3=[];
            foreach($data2 as $d)
            {
                $data3[] = (Medicine::find($d->medicine_id));
            }

            foreach ($data2 as &$order) {
                $order->trade_name = null;
                foreach ($data3 as $user) {
                    if ($order->medicine_id == $user->id) {
                        $order->trade_name = $user->trade_name;
                        break;
                    }
                }
            }

            $data4=[];
            foreach($data2 as $d)
            {
                $data4[] = (Medicine::find($d->medicine_id));
            }

            foreach ($data2 as &$order) {
                $order->price = null;
                foreach ($data4 as $user) {
                    if ($order->medicine_id == $user->id) {
                        $order->price = $user->price;
                        break;
                    }
                }
            }
            $data = [];
            $data['order'] = $data1;
            $data['sub_orders'] = $data2;

            return $this->SendResponse($data , 201 , "success");
    }

    public function historyIdForOwner(Request $request) 
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;

        $products = DB::table('orders')
        ->whereDate('created_at', '=', $request['start'])
        ->get();

        if($role == 1)
        {
            $data = $request->validate([
                'start' => ['required' ,'date'],
                'end' => ['required' ,'date'],
            ]);
            
            $data1 = (DB::table('orders')
            ->where('order_status', '=', 2)
            ->where('activate_num', '=', 0)
            ->whereDate('created_at', '>=', $request['start'])
            ->whereDate('created_at', '<=', $request['end'])
            ->get());
            $user_id = [];
            $data2=[];
            foreach($data1 as $d)
            {
                $data2[] = (User::find($d->user_id));
            }
            if($data1->isEmpty())
            {
                return $this->SendError(201 , "no orders");
            }

            foreach ($data1 as &$order) {
                $order->username = null;
                foreach ($data2 as $user) {
                    if ($order->user_id == $user->id) {
                        $order->username = $user->username;
                        break;
                    }
                }
            }
            $data = [];
            $data['order'] = $data1;

            return $this->SendResponse($data , 201 , "success");
        }
        return $this->SendError(401 , "you are not the owner");
    }

    public function historyIdForUser(Request $request) 
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;

        $products = DB::table('orders')
        ->whereDate('created_at', '=', $request['start'])
        ->get();

        if($role == 0)
        {
            $data = $request->validate([
                'start' => ['required' ,'date'],
                'end' => ['required' ,'date'],
            ]);
            
            $data1 = (DB::table('orders')
            ->where('user_id' , $userId)
            ->where('order_status', '=', 2)
            ->where('activate_num', '=', 0)
            ->whereDate('created_at', '>=', $request['start'])
            ->whereDate('created_at', '<=', $request['end'])
            ->get());
            $user_id = [];
            $data2=[];
            foreach($data1 as $d)
            {
                $data2[] = (User::find($d->user_id));
            }
            if($data1->isEmpty())
            {
                return $this->SendError(201 , "no orders");
            }

            foreach ($data1 as &$order) {
                $order->username = null;
                foreach ($data2 as $user) {
                    if ($order->user_id == $user->id) {
                        $order->username = $user->username;
                        break;
                    }
                }
            }
            $data = [];
            $data['order'] = $data1;

            return $this->SendResponse($data , 201 , "success");
        }
        return $this->SendError(401 , "you are not the owner");
    }

}
