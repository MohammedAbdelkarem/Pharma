<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Medium;

class Sub_order extends Model
{
    use HasFactory;
    protected $fillable=[
        'required_quantity',
        'order_id',
        'user_id',
        'medicine_id',
        'total_price',
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function medicines()
    {
        return $this->belongsTo(Medicine::class);
    }

}
