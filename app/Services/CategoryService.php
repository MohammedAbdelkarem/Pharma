<?php
namespace App\Services;

use App\Models\Category;
use App\Http\Resources\CategoryResource;

 
class CategoryService
{
    public function getCategories()
    {
        $categories = Category::get();

        $categories = CategoryResource::collection($categories);

        return $categories;
    }
}