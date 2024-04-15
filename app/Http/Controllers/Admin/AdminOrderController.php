<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    use ResponseTrait;

    public function getCurrentOrders(Request $request)
    {
        //return the active orders of this admin
    }

    public function modifyOrderStatus(Request $request)
    {
        //modify the order status  ,do not forget the enum
    }

    public function modifyPaymentStatus(Request $request)
    {
        //modify the payment status  ,do not forget the enum
    }

    public function getArchivedOrders(Request $request)
    {
        //get the archived orders , depending on the data(between x and y)
        //note: the initial value of the x and y shold return all the archived orders
        //so the admin when open this page should get all the orders initially(think how to do that)
    }

    public function getCustomers(Request $request)
    {
        //return all this admin customers(every user has been ever deal with this admin)
        //be careful about the user returned information
    }

    public function getCustomerOrders(Request $request)
    {
        //get the orders for that user(the customers interface will show the users)
    }
}
