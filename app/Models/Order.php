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
        'active_status',
        'price',
        'user_id',
        'admin_id',
    ];

    public function scopeCurrentAdminId($query)
    {
        return $query->where('admin_id', admin_id());
    }
    public function scopeCurrentUserId($query)
    {
        return $query->where('user_id', user_id());
    }
    public function scopeAdminId($query , $id)
    {
        return $query->where('admin_id', $id);
    }

    public function scopeActive($query)
    {
        return $query->where('active_status', 'active');
    }
    public function scopeInactive($query)
    {
        return $query->where('active_status' , 'inactive');
    }
    public function scopeOrderId($query , $id)
    {
        return $query->where('id', $id);
    }
    public function scopeDateBetween($query , $startDate , $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    public function scopeUserId($query , $id)
    {
        return $query->where('user_id' , $id);
    }



    public function updatePrice($incoming , $char)
    {
        if($char == '+')
        {
            $this->price += $incoming;
        }
        else
        {
            $this->price -= $incoming;
        }
        $this->save();
    }

    public function updateActiveStatus($status)
    {
        $this->active_status = $status;

        $this->save();
    }


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
