<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AdminMedicineController extends Controller
{
    use ResponseTrait;

    public function addMedicine(Request $request)
    {
        //validate the informations of the medicine

        //get the admin id from the token

        //store the data in the medicine table depending on the admin id

        //return response
    }

    public function updateMedicine(Request $request)
    {
        //validate the information of the medicine

        //update the medicine information
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