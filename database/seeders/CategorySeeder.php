<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Men' => ['Bags', 'Shoes', 'Clothing', 'Belts', 'Watches', 'Accessories', 'High Tech'],
            'Women' => ['Bags', 'Shoes', 'Clothing', 'Belts', 'Watches', 'Accessories', 'High Tech'],
        ];

        foreach ($categories as $categoryName => $subCategories) {

            // Create Category
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'image' => null,
                'status' => true,
            ]);

            // Create SubCategories
            foreach ($subCategories as $sub) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $sub,
                    'slug' => Str::slug($categoryName . '-' . $sub), // unique slug
                    'image' => null,
                    'status' => true,
                ]);
            }
        }
    }
}
