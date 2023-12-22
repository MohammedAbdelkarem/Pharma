<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\getOneProduct;
use Mailgun\Model\Message\SendResponse;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\getCategoryResource;

class MedicineController extends Controller
{
    use ResponseTrait;
    public function store(Request $request)
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;
        if($role == 1)
        {
            $data = $request->validate([
                'price' => ['required' ,'numeric'],
                'scientific_name' => ['required'],
                'trade_name' => ['required' , 'unique:medicines,trade_name'],
                'manufacture_company' => ['required'],
                'available_quantity' => ['required'],
                'Ed' => ['required' , 'date'],
                // 'name' => ['required'],
                'photo' => ['required'],
                'category_id' => ['required'],
            ],
            [
                'trade_name.unique' => 'this product is already exists
                to edit this product info press here',
            ]
        );
            $data['user_id'] = $userId;


            Medicine::query()->create($data);
            return $this->SendResponse($data ,201,'medicine has been added successfully');
        }
        return $this->SendError(401 , "you don't have the permission");
    }
    public function show(Request $request)
    {
        $data = Medicine::query()->where('category_id' , $request['id'])->get();
        if ($data->isEmpty()) {
            return $this->SendError(401 , "this category is empty");
        }
        return $this->SendResponse($data , 201 , 'this category products has been retrieved successfully');
    }
    public function getOneProduct(Request $request)
    {
        $data=[];
        $data['medicine'] = new getOneProduct(Medicine::find($request['id']));
        $category_id = DB::table('medicines')->where('id', $request['id'])->value('category_id');
        $data['category'] = new getCategoryResource(Category::find($category_id));
        return $this->SendResponse($data , 201,"success");
    }
    public function updateOneProduct(Request $request)
    {
        $user = Auth::user();
        $token = $request->bearerToken();
        $userId = $user->id;
        $role = $user->role;
        if($role == 1)
        {
            $data = $request->validate([
                'price' => ['required' ,'numeric'],
                'scientific_name' => ['required'],
                'trade_name' => ['required'],
                'manufacture_company' => ['required'],
                'available_quantity' => ['required'],
                'Ed' => ['required' , 'date'],
                'photo' => ['required'],
                'category_id' => ['required'],
            ],
            [
                'trade_name.unique' => 'this product is already exists
                to edit this product info press here',
            ]
        );

            $data['user_id'] = $userId;
            Medicine::query()->where('id' , $request['id'])->update($data);
            return $this->SendResponse($data ,201,'medicine has been updated successfully');
        }
        return $this->SendError(401 , "you don't have the permission");
    }

}
