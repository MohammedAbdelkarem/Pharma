<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ShowResource;
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
            return $this->SendResponse(null ,201,'medicine has been added successfully');
        }
        return $this->SendError(401 , "you don't have the permission");
    }
    public function show(Request $request)
    {
        $data = ShowResource::collection(Medicine::query()->where('category_id' , $request['id'])->get());
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
                'trade_name' => ['required', 'unique:medicines,trade_name'],
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
            $data['id'] = $request['id'];
            Medicine::query()->where('id' , $request['id'])->update($data);
            return $this->SendResponse(null ,201,'medicine has been updated successfully');
        }
        return $this->SendError(401 , "you don't have the permission");
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $results1 = getCategoryResource::collection(DB::table('categories')
        ->where('Category', 'LIKE', '%' . $searchTerm . '%')
        ->get());
        $results2 = ShowResource::collection(DB::table('medicines')
        ->where('trade_name', 'LIKE', '%' . $searchTerm . '%')
        ->get());
        $results3 = ShowResource::collection(DB::table('medicines')
        ->where('scientific_name', 'LIKE', '%' . $searchTerm . '%')
        ->get());
        if($results1->isEmpty() && $results2->isEmpty() && $results3->isEmpty())
        {
            return $this->SendError(401 , "no results");
        }
        $data = [];
        $data['category'] = $results1;

        $data['medicin'] = $results2;
        $data['medicin'] = $results3;

        return $this->SendResponse($data, 200, 'Search results retrieved successfully');
    }

}
