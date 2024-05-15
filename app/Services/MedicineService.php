<?php
namespace App\Services;

use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\{SearchResource , MedicineDetailsResource , MedicineResource , User\FavouritesResource};
 
class MedicineService
{
    private $subOrderService;

    public function __construct(SubOrderService $subOrderService)
    {
        $this->subOrderService = $subOrderService;
    }

    public function createMedicine(array $medicine)
    {
        
        $fieldsToCreate = ['scientific_name', 'manufacture_company','available_quantity', 'Ed' , 'price' , 'trade_name' , 'category_id'];

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

    public function getMedicinesByAd($adminId)
    {
        $medicines = Medicine::where('admin_id' , $adminId)->get();

        $medicines = MedicineResource::collection($medicines);

        return $medicines;
    }
    public function getMedicinesByCat($categoryId)
    {
        $medicines = Medicine::where('category_id' , $categoryId)->get();

        $medicines = MedicineResource::collection($medicines);

        return $medicines;
    }
    public function getMedicinesByCatAd($categoryId , $adminId)
    {
        $medicines = Medicine::where('category_id' , $categoryId)->where('admin_id' , $adminId)->get();

        $medicines = MedicineResource::collection($medicines);

        return $medicines;
    }

    public function getMedicineDetails($medicineId)
    {
        $medicine = Medicine::currentMedicine($medicineId)->get();

        $medicine = MedicineDetailsResource::collection($medicine);

        return $medicine;
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

    public function addToFavourites($id)
    {
        $data['user_id'] = user_id();

        $data['medicine_id'] = $id;

        $data['admin_id'] = Medicine::currentMedicine($id)->pluck('admin_id')->first();

        DB::table('favorite_medicine_user')->insert($data);
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

    public function updateMedicineQuantity($medicineId , $quantity , $char)
    {
        $medicine = Medicine::find($medicineId);

        $medicine->updateQuantity($quantity , $char);
    }
    public function updateSales($orderId)
    {
        $subOrdes = $this->subOrderService->getSubOrders($orderId);

        foreach($subOrdes as $sub)
        {
            $medicine = Medicine::find($sub['medicine_id']);

            $medicine->updateSales($sub['required_quantity'] , '+');
        }
    }
}