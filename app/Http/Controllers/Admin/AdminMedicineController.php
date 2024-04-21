<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Order;
use App\Models\Category;
use App\Models\Medicine;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\MedicineService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MedicineResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\AddMedicineRequest;
use App\Http\Requests\Admin\UpdateMedicineRequest;
use App\Http\Requests\Admin\DeleteMedincineRequest;

class AdminMedicineController extends Controller
{
    use ResponseTrait;

    private MedicineService $medicineService;
 
    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    public function addMedicine(AddMedicineRequest $request)
    {
        $validatedData = $request->validated();

        $handeledData = $this->medicineService->handleData($validatedData);

        Medicine::create($handeledData);

        return $this->SendResponse(response::HTTP_CREATED , 'medicine added successfully');
    }

    public function updateMedicine(UpdateMedicineRequest $request)
    {
        $validatedData = $request->validated();

        $validatedId = $request->validated()['id'];
        
        $ordersWithMedicine = $this->medicineService->checkMedicine($validatedId);

        if($ordersWithMedicine)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'this medicine is inculded in active orders, you can not update it');
        }

        $handeledData = $this->medicineService->handleData($validatedData);

        $medicine_id = $validatedData['id'];

        Medicine::currentMedicine($medicine_id)->update($handeledData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'medicine updated successfully');
    }

    public function deleteMedincine(DeleteMedincineRequest $request)
    {
        $validatedId = $request->validated()['id'];
        
        $ordersWithMedicine = $this->medicineService->checkMedicine($validatedId);

        if($ordersWithMedicine)
        {
            return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'this medicine is inculded in active orders, you can not delete it');
        }
        
        Medicine::currentMedicine($validatedId)->delete();

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'medicine deleted successfully');
    }

    public function getEmptyQuantities()
    {
        $data = Medicine::CuurentAdminId()->emptyMedicine()->get();

        $data = MedicineResource::collection($data);

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'data retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no empty medicines yet');
        
    }

    public function getMedicineSales(Request $request)
    {
        //return this admin medicines: the name , the sales , depending on:the entered date(between x and y)
        //get only the medicines with sales(sales > 0)
        // with the total cost of this period
    }
}