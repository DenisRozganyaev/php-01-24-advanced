<?php
namespace App\Controllers\Api;

use App\Models\Category;

class CategoriesController
{
    public Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }
}