<?php
namespace App\Services;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SearchResource;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\User\FavouritesResource;
 
class MedicineService
{

    public function createMedicine(array $medicine)
    {
        
        $fieldsToCreate = ['scientific_name', 'manufacture_company', 'available_quantity', 'Ed' , 'price' , 'trade_name' , 'category_id'];

        foreach ($fieldsToCreate as $field)
        {
            if (isset($medicine[$field]))
            {
                $data[$field] = $medicine[$field];
            }
        }
        if (isset($medicine['photo']))
        {
            $data['photo'] = photoPath($medicine['photo']);
        }
        $data['admin_id'] = admin_id();

        Medicine::create($data);
    }

    public function updateMedicine(array $medicine)
    {
        $fieldsToUpdate = ['scientific_name', 'manufacture_company', 'available_quantity', 'Ed' , 'price' , 'trade_name' , 'category_id'];

        foreach ($fieldsToUpdate as $field)
        {
            if (isset($medicine[$field]))
            {
                $data[$field] = $medicine[$field];
            }
        }
        if (isset($medicine['photo']))
        {
            $data['photo'] = photoPath($medicine['photo']);
        }

        Medicine::currentMedicine($medicine['id'])->update($data);
    }

    public function getEmptyMedicines()
    {
        $data = Medicine::CurrentAdminId()->emptyMedicine()->get();

        $data = MedicineResource::collection($data);

        return $data;
    }

    public function getAdminMedicines($categoryId)
    {
        $data = Medicine::where('category_id' , $categoryId)->CurrentAdminId()->get();

        $data = MedicineResource::collection($data);

        return $data;
    }

    public function addToFavourites($id)
    {
        $data['user_id'] = user_id();

        $data['medicine_id'] = $id;

        $data['admin_id'] = Medicine::currentMedicine($id)->pluck('admin_id')->first();

        DB::table('favorite_medicine_user')->insert($data);
    }

    public function getFavourites()
    {
        $records = DB::table('favorite_medicine_user')->where('user_id' , user_id())->get();

        if($records->isEmpty())
        {
            return null;
        }

        foreach($records as $record)
        {
            $data[] = new FavouritesResource(Medicine::find($record->medicine_id));
        }
        return $data;
    }

    public function results($field)
    {
        $medicines = Medicine::where('trade_name', 'LIKE', "%$field%")
        ->orWhere('scientific_name', 'LIKE', "%$field%")
        ->orWhere('manufacture_company', 'LIKE', "%$field%")
        ->get();

        $medicines = SearchResource::collection($medicines);

        return $medicines;
    }
}