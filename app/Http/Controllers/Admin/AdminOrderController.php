<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ResponseTrait;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\ArchivedOrdersRequest;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\UpdatePaymentStatusRequest;
use App\Http\Requests\IdRequest;

class AdminOrderController extends Controller
{
    use ResponseTrait;

    private OrderService $orderService;
 
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getCurrentOrders()
    {
        $data = $this->orderService->getAdminActiveOrders();

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no active orders');
    }

    public function modifyOrderStatus(UpdateOrderStatusRequest $request)
    {
        $validatedData = $request->validated();

        $this->orderService->updateOrder($validatedData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order status updated successfully');
    }

    public function modifyPaymentStatus(UpdatePaymentStatusRequest $request)
    {
        $validatedData = $request->validated();
        
        $this->orderService->updatePayment($validatedData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'payment status updated successfully');
    }

    public function getArchivedOrders(ArchivedOrdersRequest $request)
    {
        $validatedData = $request->validated();

        $data = $this->orderService->getAdminArchivedOrders($validatedData);

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no orders yet');
        }

        return $this->sendResponse(response::HTTP_OK , 'data retrieved successfully' , $data);
    }

    public function getCustomers()
    {
        $data = $this->orderService->getCustomers();

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no customers yet');
        }

        return $this->sendResponse(response::HTTP_OK , 'customers retrieved successfully' , $data);
    }

    public function getCustomerOrders(IdRequest $request)
    {
        $userId = $request->validated()['id'];

        $data = $this->orderService->getCustomerOrders($userId);

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no orders yet');
        }

        return $this->sendResponse(response::HTTP_OK , 'customer orders retrieved successfully' , $data);
    }
}
