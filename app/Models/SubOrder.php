<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use HasFactory;

    protected $fillable=[
        'required_quantity',
        'total_price',
        'order_id',
        'medicine_id',
    ];

    public function scopeOrders($query , $array)
    {
        return $query->whereIn('order_id', $array);
    }
    public function scopeMedicines($query , $id)
    {
        return $query->where('medicine_id', $id);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
