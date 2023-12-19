<?php

namespace App\Models;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['Category'];

    public function medicines()
    {
        return $this->hasmany(Medicine::class , 'category_id');
    }
}
