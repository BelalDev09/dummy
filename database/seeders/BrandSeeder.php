<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Rolex',
                'country' => 'Switzerland',
                'website' => 'https://www.rolex.com',
                'description' => 'Luxury Swiss watch manufacturer.',
            ],
            [
                'name' => 'Gucci',
                'country' => 'Italy',
                'website' => 'https://www.gucci.com',
                'description' => 'High-end fashion and leather goods brand.',
            ],
            [
                'name' => 'Nike',
                'country' => 'USA',
                'website' => 'https://www.nike.com',
                'description' => 'Global sportswear brand.',
            ],
            [
                'name' => 'Adidas',
                'country' => 'Germany',
                'website' => 'https://www.adidas.com',
                'description' => 'Sports clothing and accessories.',
            ],
            [
                'name' => 'Puma',
                'country' => 'Germany',
                'website' => 'https://www.puma.com',
                'description' => 'Athletic and casual footwear brand.',
            ],
            [
                'name' => 'Zara',
                'country' => 'Spain',
                'website' => 'https://www.zara.com',
                'description' => 'Fast fashion clothing brand.',
            ],
            [
                'name' => 'H&M',
                'country' => 'Sweden',
                'website' => 'https://www.hm.com',
                'description' => 'Affordable fashion retailer.',
            ],
            [
                'name' => 'Louis Vuitton',
                'country' => 'France',
                'website' => 'https://www.louisvuitton.com',
                'description' => 'Luxury bags and fashion brand.',
            ],
            [
                'name' => 'Apple',
                'country' => 'USA',
                'website' => 'https://www.apple.com',
                'description' => 'Premium technology products and electronics brand.',
            ],
            [
                'name' => 'Samsung',
                'country' => 'South Korea',
                'website' => 'https://www.samsung.com',
                'description' => 'Global leader in electronics and smart devices.',
            ],
            [
                'name' => 'Sony',
                'country' => 'Japan',
                'website' => 'https://www.sony.com',
                'description' => 'Consumer electronics, gaming and entertainment products.',
            ],

            [
                'name' => 'HP',
                'country' => 'USA',
                'website' => 'https://www.hp.com',
                'description' => 'Personal computers, printers and IT hardware.',
            ],

            [
                'name' => 'Xiaomi',
                'country' => 'China',
                'website' => 'https://www.mi.com',
                'description' => 'Smartphones and smart home devices brand.',
            ],
            [
                'name' => 'ASUS',
                'country' => 'Taiwan',
                'website' => 'https://www.asus.com',
                'description' => 'Laptops, gaming hardware and electronics.',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'logo' => null,
                'image' => null,
                'banner' => null,
                'description' => $brand['description'],
                'country' => $brand['country'],
                'website' => $brand['website'],
                'status' => true,
            ]);
        }
    }
}
