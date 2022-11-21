<?php

namespace Tests\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public static function getRandomCategory(): Category
    {
        return Category::inRandomOrder()->first();
    }

    public static function getTotalCategoriesCount(): int
    {
        return Category::count();
    }
}
