<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'order_status',
        'payment_status',
        'activate_num',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function sub_orders()
    {
        return $this->hasmany(Sub_order::class , 'order_id');
    }
}
