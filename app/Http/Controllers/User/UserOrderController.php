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
        //just take the medicine and add it to this table
        //remember to make the requiered calculations like the total price and the other things
        //also make sure to bind it with the active order id correctly.

        $medicineId = $request->validated()['medicine_id'];
        $requiredPQuantity = $request->validated()['required_quantity'];
        $admin_id = Medicine::find($medicineId)->pluck('admin_id')->first();

        $data['required_quantity'] = $requiredPQuantity;
        $data['medicine_id'] = $medicineId;

        $oneItemPrice = Medicine::currentMedicine($medicineId)->pluck('price')->first();
        $oldQuantity = Medicine::currentMedicine($medicineId)->pluck('available_quantity')->first();

        $data['total_price'] = $oneItemPrice * $requiredPQuantity;

        $data['order_id'] = Order::currentUserId()->active()->pluck('id')->first();
         //dd($data['order_id']);
        $new_quantity = $oldQuantity - $requiredPQuantity;

        Medicine::currentMedicine($medicineId)->update([
            'available_quantity' => $new_quantity
        ]);
        Order::currentUserId()->update([
            'admin_id' => $admin_id
        ]);

        SubOrder::create($data);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'added to cart successfully');


        //$data to create: 
        //requiered quantity:from the front
        //total price:from us(using the medicine id , multiple the req quantity by the medicine id)
        //medicine id:form the front
        //order id:from us(the only active order for that user)
        //we also here need the user id(take it from the helpers)
        //do not forget to change the order price(add the price of the suborder to it)


        //stopping here , modify all the order topic
        //we will not create an empty order so delete it from the controllers of register and submit order and delete the order service
        //we will create an order active for every admin , only when the user create sub order for that admin
        //the user has one active order for the one admin
        //in the method above::change the name to::add to cart , then firstly create a new order for that user and then add the suborder to it
        //change the old implementation
        //if there is an active order currentluy fo that user and that admin  , then do not create another one and add to the current order
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
