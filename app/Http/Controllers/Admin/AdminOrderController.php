<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArchivedOrdersRequest;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerOrderRequest;
use App\Http\Resources\Admin\OrderResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\UpdatePaymentStatusRequest;
use App\Http\Resources\Admin\CustomerResource;

class AdminOrderController extends Controller
{
    use ResponseTrait;

    public function getCurrentOrders()
    {
        //return the active orders of this admin

        $data = Order::currentAdminId()->active()->get();

        $data = OrderResource::collection($data);

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no active orders');
    }

    public function modifyOrderStatus(UpdateOrderStatusRequest $request)
    {
        //modify the order status  ,do not forget the enum
        $newStatus = $request->validated();
        
        $orderId = $request->validated()['id'];
        
        Order::OrderId($orderId)->update($newStatus);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order status updated successfully');
    }

    public function modifyPaymentStatus(UpdatePaymentStatusRequest $request)
    {
        //modify the payment status  ,do not forget the enum

        $newStatus = $request->validated();
        
        $orderId = $request->validated()['id'];
        
        Order::OrderId($orderId)->update($newStatus);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'payment status updated successfully');
    }

    public function getArchivedOrders(ArchivedOrdersRequest $request)
    {
        //get the archived orders , depending on the date(between x and y)
        //note: the initial value of the x and y shold return all the archived orders
        //so the admin when open this page should get all the orders initially(think how to do that)

        //select every thing from orders in between(the first date whaich is the first order created at , and the last date which is now) where (the admin id is admin_id()) where (the active_status is inactive)
        $validatedData = $request->validated();

        $startDate = isset($validatedData['start'])
        ? Carbon::parse($validatedData['start'])
        : Order::min('created_at');

        $endDate = isset($validatedData['end'])
        ? Carbon::parse($validatedData['end'])
        : Carbon::now();
        
        $data = Order::currentAdminId()->inactive()->dateBetween($startDate , $endDate)->get();

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no orders yet');
        }

        $data = OrderResource::collection($data);

        return $this->sendResponse(response::HTTP_OK , 'data retrieved successfully' , $data);
    }

    public function getCustomers()
    {
        $data = Order::currentAdminId()->active()->get();

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no customers yet');
        }

        $data = CustomerResource::collection($data);

        return $this->sendResponse(response::HTTP_OK , 'customers retrieved successfully' , $data);
    }

    public function getCustomerOrders(CustomerOrderRequest $request)
    {
        $userId = $request->validated()['user_id'];

        $data = Order::userId($userId)->currentAdminId()->get();

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no orders yet');
        }

        $data = OrderResource::collection($data);

        return $this->sendResponse(response::HTTP_OK , 'customers retrieved successfully' , $data);
    }
}
