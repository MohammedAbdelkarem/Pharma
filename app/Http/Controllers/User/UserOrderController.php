<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function createOrderUtility()
    {
        //do it on the services for the user when he logged on or register
        //check if he has an active order, if he is not, create one of him
        //also remember that when the order is submitted , we have to make him disactive and then create another one
        
        //we can make a service that check if the user has an order or not, then depending on that it will create one or just skip
        //after that  , call this service in your controllers(remember to call it in the register)
        //well actually , we will call this service only when the user register, or when the user submit the order.so there is no need for the checking inside this service(just create the new active order).
    }

    public function createSubOrder(Request $request)
    {
        //just take the medicine and add it to this table
        //remember to make the requiered calculations like the total price and the other things
        //also make sure to bind it with the active order id correctly.
    }

    public function submitOrder(Request $request)
    {
        //here we will do alot of things

        //discount the quantities from the medicines table

        //close the order 

        //create another one(by the sevice)
        //$this->orderService->createOrder($admin->id);
    }

    public function getOrders(Request $request)
    {
        //get this user orders(including the current order)
    }
}
