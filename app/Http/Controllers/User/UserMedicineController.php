<?php

namespace App\Http\Controllers\User;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\medicine_user;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineIdRequest;
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
        $data = DB::table('favorite_medicine_user')->where('user_id' , user_id())->get();

        if($data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_NO_CONTENT , 'no favourites yet');
        }
        $data = FavouritesResource::collection($data);
        return $this->sendResponse(response::HTTP_OK , 'favorites retrieved successfully' , $data);
    }

    public function searchForMedicine(Request $request)
    {
        //generally search in the medicines table
    }
}
