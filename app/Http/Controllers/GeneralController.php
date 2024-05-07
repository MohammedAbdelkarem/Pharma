<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Requests\AdCatRequest;
use App\Http\Resources\AdminResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MedicineResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\MedicineDetailsResource;
use App\Http\Resources\OrderDetailsResource;
use App\Services\AdminService;
use App\Services\CategoryService;
use App\Services\MedicineService;
use App\Services\OrderService;

class GeneralController extends Controller
{
    use ResponseTrait;

    private CategoryService $categoryService;
    private AdminService $adminService;
    private MedicineService $medicineService;
    private OrderService $orderService;
 
    public function __construct(CategoryService $categoryService , AdminService $adminService , MedicineService $medicineService , OrderService $orderService)
    {
        $this->categoryService = $categoryService;
        $this->adminService = $adminService;
        $this->medicineService = $medicineService;
        $this->orderService = $orderService;
    }
    public function getCategories()
    {
        $data = $this->categoryService->getCategories();

        return $this->SendResponse(response::HTTP_OK , 'categories retrieved successfully' , $data);
    }

    public function getAdmins()
    {
        $data = $this->adminService->getAdmins();

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'admins retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no admins yet');
    }


    public function getMedicianesByAdmin(IdRequest $request)
    {
        $adminId = $request->validated()['id'];

        $data = $this->medicineService->getMedicinesByAd($adminId);

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no medicines yet');
    }

    public function getMedicineDetails(IdRequest $request)
    {
        $medicineId = $request->validated()['id'];

        $data = $this->medicineService->getMedicineDetails($medicineId);

        return $this->SendResponse(response::HTTP_OK , 'medicine details retrieved successfully' , $data);
    }

    public function getOrderDetails(IdRequest $request)
    {
        $orderId = $request->validated()['id'];

        $data = $this->orderService->getOrderDetails($orderId);

        return $this->sendResponse(response::HTTP_OK , 'order details retrieved successfully' , $data);
    }
}
