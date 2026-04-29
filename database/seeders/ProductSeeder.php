<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Brand;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::with('subCategories')->get();
        $brands = Brand::all();

        foreach ($categories as $category) {

            foreach ($category->subCategories as $sub) {

                //  product
                for ($i = 1; $i <= 2; $i++) {

                    $productName = $category->name . ' ' . $sub->name . ' ' . $i;

                    // random brand (safe check)
                    $brand = $brands->isNotEmpty() ? $brands->random() : null;

                    $product = Product::create([
                        'category_id' => $category->id,
                        'sub_category_id' => $sub->id,
                        'brand_id' => $brand?->id,
                        'name' => $productName,
                        'slug' => Str::slug($productName) . '-' . uniqid(),
                        'sku' => 'VAR-' . strtoupper(Str::random(8)),
                        'short_description' => 'Demo short description',
                        'description' => 'Demo full description for ' . $productName,
                        'price' => rand(500, 3000),
                        'discount_price' => null,
                        'stock' => rand(10, 100),
                        'thumbnail' => null,
                        'gallery' => [],
                        'material' => 'Cotton',
                        'weight' => 1.00,
                        'dimensions' => '10x10x10',
                        'tags' => ['fashion', strtolower($sub->name)],
                        'is_featured' => rand(0, 1),
                        'status' => true,
                    ]);

                    // Variant logic
                    if (in_array(strtolower($sub->name), ['clothing', 'shoes'])) {

                        $sizes = ['S', 'M', 'L', 'XL'];
                        $colors = [
                            ['name' => 'Red', 'hex' => '#ff0000'],
                            ['name' => 'Black', 'hex' => '#000000'],
                            ['name' => 'White', 'hex' => '#ffffff'],
                        ];

                        foreach ($sizes as $size) {
                            foreach ($colors as $color) {

                                ProductVariant::create([
                                    'product_id' => $product->id,
                                    'size' => $size,
                                    'color' => $color['name'],
                                    'color_hex' => $color['hex'],
                                    'sku' => strtoupper(Str::random(10)),
                                    'price' => $product->price + rand(0, 200),
                                    'stock' => rand(1, 20),
                                    'status' => true,
                                ]);
                            }
                        }
                    } else {
                        // simple product
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => null,
                            'color' => 'Default',
                            'color_hex' => '#000000',
                            'sku' => strtoupper(Str::random(10)),
                            'price' => $product->price,
                            'stock' => rand(5, 20),
                            'status' => true,
                        ]);
                    }
                }
            }
        }
    }
}
