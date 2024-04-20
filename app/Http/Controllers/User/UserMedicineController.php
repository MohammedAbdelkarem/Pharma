<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserMedicineController extends Controller
{

    public function addMedicineToFavourites(Request $request)
    {
        //add the medicine to favourites
    }

    public function getFavourites(Request $request)
    {
        //get the favourites for that user
    }

    public function searchForMedicine(Request $request)
    {
        //generally search in the medicines table
    }
}
