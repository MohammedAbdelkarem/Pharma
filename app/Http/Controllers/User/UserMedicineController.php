<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\IdRequest;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MedicineService;

class UserMedicineController extends Controller
{
    use ResponseTrait;

    private MedicineService $medicineService;
 
    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }
    public function addMedicineToFavourites(IdRequest $request)
    {
        $medicineId = $request->validated()['id'];

        $this->medicineService->addToFavourites($medicineId);

        return $this->sendResponse(response::HTTP_NO_CONTENT , 'added to favourites successfully');
    }

    public function getFavourites()
    {
        $data = $this->medicineService->getFavourites();

        if(!$data)
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'no favourites yet');
        }
        return $this->sendResponse(response::HTTP_OK , 'favorites retrieved successfully' , $data);
    }

    public function searchForMedicine(Request $request)
    {
        $searchFor = $request->field;
        
        $medicines = $this->medicineService->results($searchFor);

        if($medicines->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'no results');
        }

        return $this->sendResponse(response::HTTP_OK , 'results retrieved successfully' , $medicines);
    }
}
