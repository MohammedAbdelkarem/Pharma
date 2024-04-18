<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeAdminId($query , $id)
    {
        return $query->where('admin_id' , $id);
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
