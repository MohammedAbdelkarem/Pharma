<?php
namespace App\Services;
 
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
}