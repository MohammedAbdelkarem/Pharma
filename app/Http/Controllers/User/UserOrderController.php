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
use App\Services\OrderService;
use App\Services\SubOrderService;
use Symfony\Component\HttpFoundation\Response;

class UserOrderController extends Controller
{

    use ResponseTrait;

    private OrderService $orderService;
    private SubOrderService $subOrderService;
 
    public function __construct(OrderService $orderService , SubOrderService $subOrderService)
    {
        $this->orderService = $orderService;
        $this->subOrderService = $subOrderService;
    }
    public function createSubOrder(SubOrderRequest $request)
    {
        $validatedData = $request->validated();
        
        $this->orderService->createSubOrder($validatedData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'added to cart successfully');
    }

    public function deleteOrder(IdRequest $request)
    {
        $orderId = $request->validated()['id'];

        $this->orderService->deleteOrder($orderId);
        
        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order deleted successfully');
    }
    public function deleteSubOrder(IdRequest $request)
    {
        $subOrderId = $request->validated()['id'];
        
        $this->subOrderService->deleteSubOrder($subOrderId);

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
