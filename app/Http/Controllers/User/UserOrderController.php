<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Medicine;
use App\Models\SubOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\User\SubOrderRequest;

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
        $oldOrderPrice = Order::find($orderId)->pluck('price')->first();
        $oldOrderPrice += $data['total_price'];
        Order::find($orderId)->update(['price' => $oldOrderPrice]);

        //modify the Medicine quantity
        $oldQuantity = Medicine::find($medicineId)->pluck('available_quantity')->first();
        $oldQuantity -= $requiredQuantity;
        Medicine::find($medicineId)->update(['available_quantity' => $oldQuantity]);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'added to cart successfully');
    }

    public function deleteOrder(Request $request)
    {
        // delete all the order and the sub orders
        //return back the quantities
    }
    public function deleteSubOrder(Request $request)
    {
        // delete the suborder 
        //return back the quantitiues
        //modify the order price
        //if the order has no suborders anymore(empty order) , then delete it
    }

    public function submitOrder(Request $request)
    {
        // change the status of this order 
        //fill the sales field in the medicine record
    }

    public function getOrders(Request $request)
    {
        //get this user orders(including the current order)
    }
}
