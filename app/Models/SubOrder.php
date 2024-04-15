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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
