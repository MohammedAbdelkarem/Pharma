<?php
namespace App\Services;

use App\Models\Order;
use App\Models\SubOrder;
 
class MedicineService
{

    public function handleData(array $medicine)
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
        $data['admin_id'] = admin_id();

        return $data;
    }

    public function checkMedicine($id)
    {
        // Get the active orders for the admin
        $activeOrders = Order::AdminId()->Active()->pluck('id');

        // Check if any of the active orders contain the medicine ID
        $ordersWithMedicine = SubOrder::Orders($activeOrders)->Medicines($id)->exists();

        return $ordersWithMedicine;
    }
}