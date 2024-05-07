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
use App\Services\MedicineService;
use App\Services\OrderService;
use App\Services\SubOrderService;
use Symfony\Component\HttpFoundation\Response;

class UserOrderController extends Controller
{

    use ResponseTrait;

    private OrderService $orderService;

    private SubOrderService $subOrderService;
    private MedicineService $medicineService;
 
    public function __construct(OrderService $orderService , SubOrderService $subOrderService , MedicineService $medicineService)
    {
        $this->orderService = $orderService;
        $this->subOrderService = $subOrderService;
        $this->medicineService = $medicineService;
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
        $validatedData = $request->validated();
        $orderId = $request->validated()['id'];

        $this->orderService->updateOrder($validatedData);

        $this->medicineService->updateSales($orderId);
        

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'order submitted successfully');
    }

    public function getOrders()
    {
        $data = $this->orderService->getUserOrders();

        if ($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'there is no orders yet');
        }

        return $this->SendResponse(response::HTTP_OK , 'orders retrieved successfully' , $data);
    }
}
