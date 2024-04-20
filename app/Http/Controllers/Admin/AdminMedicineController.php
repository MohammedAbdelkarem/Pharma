<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\MedicineService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\AddMedicineRequest;
use App\Http\Requests\Admin\UpdateMedicineRequest;

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

        $handeledData = $this->medicineService->handleData($validatedData);

        $medicine_id = $validatedData['id'];
        // dd($medicine_id);

        Medicine::currentMedicine($medicine_id)->update($handeledData);
        // dd($handeledData);

        return $this->SendResponse(response::HTTP_CREATED , 'medicine updated successfully');
    }

    public function deleteMedincine(Request $request)
    {
        //delete the medicine from the medicine table
    }

    public function getEmptyQuantities(Request $request)
    {
        //return the medicines of zero quantity
    }

    public function getMedicineSales(Request $request)
    {
        //return this admin medicines: the name , the sales , depending on:the entered date(between x and y)
        //get only the medicines with sales(sales > 0)
        // with the total cost of this period
    }

    public function getAllMedicines(Request $request)
    {
        //get all the admin medicines sorted by the category 
        //ex:the first category medicines, then the second one medicines, and so on.
    }
}