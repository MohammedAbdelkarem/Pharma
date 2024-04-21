<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scientific_name',
        'trade_name',
        'manufacture_company',
        'available_quantity',
        'Ed',
        'price',
        'photo',
        'sales',
        'admin_id',
        'category_id',
    ];

    public function scopeCurrentMedicine($query , $id)
    {
        return $query->where('id' , $id);
    }
    public function scopeEmptyMedicine($query)
    {
        return $query->where('available_quantity' , 0);
    }

    public function scopeCuurentAdminId($query)
    {
        return $query->where('admin_id' , admin_id());
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favorite_medicine_user', 'medicine_id', 'user_id');
    }


}
