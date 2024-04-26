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

class GeneralController extends Controller
{
    use ResponseTrait;
    public function getCategories(Request $request)
    {
        //get the categories from the category table

        $data = Category::get();

        $data = CategoryResource::collection($data); 

        return $this->SendResponse(response::HTTP_OK , 'categories retrieved successfully' , $data);
    }

    public function getAdmins()
    {
        $data = Admin::get();

        $data = AdminResource::collection($data); 

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'admins retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no admins yet');
    }

    public function getMedicinesByCategory(IdRequest $request)//use the id request file
    {
        //get this category medicines

        $categoryId = $request->validated()['id'];

        $data = Medicine::where('category_id' , $categoryId)->get();//use resource

        $data = MedicineResource::collection($data);
        
        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'medicines retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no medicines yet');
    }

    public function getMedicinesByAdminAndCategory(AdCatRequest $request)
    {
        //return the medicines from the medicine table depending on the admin id and category id

        $categoryId = $request->validated()['category_id'];
        $adminId = $request->validated()['admin_id'];

        $data = Medicine::where('category_id' , $categoryId)->where('admin_id' , $adminId)->get();
        
        $data = MedicineResource::collection($data);

        if(!$data->isEmpty())
        {
            return $this->sendResponse(response::HTTP_OK , 'medicines retrieved succussfully' , $data);
        }
        return $this->sendResponse(response::HTTP_NO_CONTENT , 'no medicines yet');
    }

    public function getMedicineDetails(IdRequest $request)//use the id requesr file
    {
        $medicineId = $request->validated()['id'];

        $data = Medicine::where('id' , $medicineId)->get(); // use resouce for the returned data

        $data = MedicineDetailsResource::collection($data);

        return $this->SendResponse(response::HTTP_OK , 'medicine details retrieved successfully' , $data);
        //search about the find if we can use it with another things 
    }

    public function getOrderDetails(IdRequest $request)
    {
        $orderId = $request->validated()['id'];

        $data = Order::where('id' , $orderId)->get();

        $data = OrderDetailsResource::collection($data);

        return $this->sendResponse(response::HTTP_OK , 'order details retrieved successfully' , $data);
    }
}
