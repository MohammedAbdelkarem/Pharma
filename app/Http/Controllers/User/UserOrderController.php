<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderIdRequest;
use App\Http\Requests\User\SubOrderRequest;
use App\Http\Resources\User\OrderResource;
use Symfony\Component\HttpFoundation\Response;

class UserOrderController extends Controller
{

    use ResponseTrait;
    public function createSubOrder(SubOrderRequest $request)
    {
        $validatedData = $request->validated();

        $adminId = $validatedData['admin_id'];
        $medicineId = $validatedData['medicine_id'];
        $requiredQuantity = $validatedData['required_quantity'];
        

        //check if there is an active order or not
        $activeOrders = Order::active()->currentUserId()->adminId($adminId)->get();

        //if there is no active order then create one
        if($activeOrders->isEmpty())
        {
            $data['user_id'] = user_id();
            $data['admin_id'] = $validatedData['admin_id'];
            Order::create($data);
        }
        //get the order id
        $orderId = Order::active()->currentUserId()->adminId($adminId)->pluck('id')->first();

        //cleat the prevouis array to use it again
        unset($data);
        

        //preaper the data to create the suborder
        $data['required_quantity'] = $requiredQuantity;
        $data['medicine_id'] = $medicineId;
        $data['order_id'] = $orderId;

        $oneItemPrice = Medicine::currentMedicine($medicineId)->pluck('price')->first();

        $data['total_price'] = $oneItemPrice * $requiredQuantity;

        SubOrder::create($data);

        //modify the order price

        $order = Order::find($orderId);
        $order->updatePrice($data['total_price'] , '+');

        //modify the Medicine quantity

        $medicine = Medicine::find($medicineId);
        $medicine->updateQuantity($requiredQuantity , '-');

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'added to cart successfully');
    }

    public function deleteOrder(IdRequest $request)
    {
        $orderId = $request->validated()['id'];
        //dd($orderId);

        $subOrders = SubOrder::where('order_id' , $orderId)->get();

        foreach($subOrders as $sub)
        {
            $medicineId = $sub['medicine_id'];

            $quantity = $sub['required_quantity'];
            

            $medicine = Medicine::find($medicineId);
            $medicine->updateQuantity($quantity , '+');
        }

        Order::find($orderId)->delete();
        
        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order deleted successfully');
    }
    public function deleteSubOrder(IdRequest $request)
    {
        $subOrderId = $request->validated()['id'];//create a request file that take a id and use it with the two last controllers(check what is them)
        //return back the quantitiues
        // dd($subOrderId);
        $subOrder = SubOrder::where('id' , $subOrderId)->first();
        // dd($subOrder);

        $medicineId = $subOrder['medicine_id'];
        // dd($medicineId);

        $orderId = $subOrder['order_id'];

        $quantity = $subOrder['required_quantity'];

        $subOrderPrice = $subOrder['total_price'];

        //check if we can update the quantity like that
        $medicine = Medicine::find($medicineId);
        $medicine->updateQuantity($quantity , '+');

        $orderPrice = Order::find($orderId)->pluck('price')->first();

        if($orderPrice == $subOrderPrice) // if this subOrder is the last suborder in this order
        {
            Order::find($orderId)->delete(); //delete the order directly
        }
        else //if there is another subOrders
        {
            $order = Order::find($orderId);
            $order->updatePrice($subOrderPrice, '-');//modify the order price
            subOrder::find($subOrderId)->delete(); // delete the subOrder
        }
        
        return $this->SendResponse(response::HTTP_NO_CONTENT , 'suborder deleted successfully');
    }

    public function submitOrder(IdRequest $request)
    {
        // change the status of this order 

        $orderId = $request->validated()['id'];

        $subOrders = SubOrder::where('order_id' , $orderId)->get();
        $data=[];
        foreach($subOrders as $sub) // medicine_id => quantity to add to the sales
        {
            $data[$sub['medicine_id']] = $sub['required_quantity'];
        }
        // dd($data);
        //update the sales of the medicine
        foreach($data as $key => $value)
        {
            $medicine = Medicine::find($key);
            $medicine->updateSales($value , '+');
        }

        //update the active order status
        $order = Order::find($orderId);
        $order->updateActiveStatus('inactive');

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order submitted successfully');
    }

    public function getOrders(Request $request)
    {
        $data = Order::where('user_id' , user_id())->get();

        //make changes pn the returned data using a resource
        $data = OrderResource::collection($data);
        return $this->SendResponse(response::HTTP_OK , 'orders retrieved successfully' , $data);
    }
}
