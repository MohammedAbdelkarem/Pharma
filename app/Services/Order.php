<?php
namespace App\Services;

use App\Models\Order;
 
class OrderService
{
    public function createOrder($id)
    {
        Order::create([
            'user_id' => $id
        ]);
    }
}