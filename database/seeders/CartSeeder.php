<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // Create Cart
        $cartId = DB::table('carts')->insertGetId([
            'user_id' => 1, // ensure exists from UserSeeder
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get products with variants
        $products = Product::with('variants')->inRandomOrder()->take(3)->get();

        foreach ($products as $product) {

            $variant = $product->variants->first();

            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'quantity' => rand(1, 3),
                'price' => $variant?->price ?? $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
