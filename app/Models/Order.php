<?php

namespace App\Models;

use App\Enums\Active;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
        'order_status',
        'payment_status',
        'active',
        'price',
        'user_id',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sub_orders()
    {
        return $this->hasMany(SubOrder::class);
    }
}
