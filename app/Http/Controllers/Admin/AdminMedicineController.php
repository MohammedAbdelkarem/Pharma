<?php

namespace App\Http\Controllers\Admin;

use App\Models\Medicine;
use App\Traits\ResponseTrait;
use App\Services\{MedicineService , OrderService};
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\IdRequest;
use App\Http\Requests\Admin\{AddMedicineRequest , UpdateMedicineRequest};

class AdminMedicineController extends Controller
{
    use ResponseTrait;

    private MedicineService $medicineService;
    private OrderService $orderService;
 
    public function __construct(MedicineService $medicineService , OrderService $orderService)
    {
        $this->medicineService = $medicineService;
        $this->orderService = $orderService;
    }

    public function addMedicine(AddMedicineRequest $request)
    {
        $validatedData = $request->validated();

        $this->medicineService->createMedicine($validatedData);

        return $this->SendResponse(response::HTTP_CREATED , 'medicine added successfully');
    }

    public function updateMedicine(UpdateMedicineRequest $request)
    {
        $validatedData = $request->validated();

        $medicineId = $request->validated()['id'];
        
        $ordersWithMedicine = $this->orderService->checkMedicine($medicineId);

        if($ordersWithMedicine)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'this medicine is inculded in active orders, you can not update it until the order is done');
        }

        $this->medicineService->updateMedicine($validatedData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'medicine updated successfully');
    }

    public function deleteMedincine(IdRequest $request)
    {
        $medicineId = $request->validated()['id'];
        
        $ordersWithMedicine = $this->orderService->checkMedicine($medicineId);

        if($ordersWithMedicine)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'this medicine is inculded in active orders, you can not delete it until the order is done');
        }
        
        Medicine::currentMedicine($medicineId)->delete();

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'medicine deleted successfully');
    }

    public function getEmptyQuantities()
    {
        $data = $this->medicineService->getEmptyMedicines();

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no empty medicines yet');
    }

    public function getAdminMedicines(IdRequest $request)
    {
        $categoryId = $request->validated()['id'];

        $data = $this->medicineService->getAdminMedicines($categoryId);

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no medicines yet');
    }
}