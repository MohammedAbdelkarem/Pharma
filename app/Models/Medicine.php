<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        // 'name',
        'scientific_name',
        'trade_name',
        'manufacture_company',
        'available_quantity',
        'Ed',
        'price',
        'photo',
        'user_id',
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
