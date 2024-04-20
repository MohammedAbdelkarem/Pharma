<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function getCategories(Request $request)
    {
        //get the categories from the category table
    }

    public function getAdmins(Request $request)
    {
        //get the admins from the admins table
    }

    public function getMedicinesByCategory(Request $request)
    {
        //get this category medicines
    }

    public function getMedicinesByAdminAndCategory(Request $request)
    {
        //return the medicines from the medicine table depending on the admin id and category id
    }

    public function getMedicineDetails(Request $request)
    {
        //get the medicine details from the medicine table
    }

    public function getOrderDetails(Request $request)
    {
        //get the order details from the medicine order
        //we also here need to return the suborders with him
    }

    /*
    note about the last two mehtods:
    the medicine mehthod data deffers between the admin call and the user call

    for ex: the admin will not need to see his name returned , while the user do
    */

}
