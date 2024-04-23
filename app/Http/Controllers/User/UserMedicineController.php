<?php

namespace App\Http\Controllers\User;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\medicine_user;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineIdRequest;
use App\Http\Resources\Admin\MedicineResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\User\FavouritesResource;

class UserMedicineController extends Controller
{

    use ResponseTrait;
    public function addMedicineToFavourites(MedicineIdRequest $request)
    {
        $medicineId = $request->validated()['id'];

        $data['user_id'] = user_id();

        $data['medicine_id'] = $medicineId;

        $data['admin_id'] = Medicine::currentMedicine($medicineId)->pluck('admin_id')->first();

        DB::table('favorite_medicine_user')->insert($data);

        return $this->sendResponse(response::HTTP_NO_CONTENT , 'added to medicine successfully');
    }

    public function getFavourites()
    {
        $records = DB::table('favorite_medicine_user')->where('user_id' , user_id())->get();

        if($records->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'no favourites yet');
        }
        foreach($records as $record)
        {
            $data[] = new FavouritesResource(Medicine::find($record->medicine_id));
        }
        return $this->sendResponse(response::HTTP_OK , 'favorites retrieved successfully' , $data);
    }

    public function searchForMedicine(Request $request)
    {
        $searchFor = $request->field;

        $medicines = Medicine::where('trade_name', 'LIKE', "%$searchFor%")
        ->orWhere('scientific_name', 'LIKE', "%$searchFor%")
        ->orWhere('manufacture_company', 'LIKE', "%$searchFor%")
        ->get();

        if($medicines->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'no results');
        }
        
        $medicines = MedicineResource::collection($medicines);

        return $this->sendResponse(response::HTTP_OK , 'results retrieved successfully' , $medicines);
    }
}
