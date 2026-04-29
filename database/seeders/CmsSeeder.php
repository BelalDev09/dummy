<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CMS;
use Illuminate\Support\Carbon;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ================= CATEGORY SECTION =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'category_section',
            'type' => 'json',
            'main_text' => 'Shop By Category',

            'v1' => [
                'title' => 'Men Fashion',
                'sub_title' => 'Latest Collection',
                'image' => null,
                'button_text' => 'Shop Now',
                'button_link' => 'https://men.com'
            ],

            'v2' => [
                'title' => 'Women Fashion',
                'sub_title' => 'New Arrival',
                'image' => null,
                'button_text' => 'Explore',
                'button_link' => 'https://women.com'
            ],

            'status' => 'active',
        ]);

        // ================= HIGH TECH =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'high_tech_section',
            'type' => 'content',

            'title' => 'High Tech Products',
            'sub_title' => 'Latest gadgets & electronics',
            'button_text' => 'Shop Now',
            'link_url' => '/high-tech',

            'status' => 'active',
        ]);

        // ================= MEN COLLECTION =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'men_collection_section',
            'type' => 'content',

            'title' => 'Men Collection',
            'sub_title' => 'Stylish outfits for men',
            'button_text' => 'View Collection',
            'link_url' => '/men',

            'status' => 'active',
        ]);

        // ================= WOMEN COLLECTION =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'women_collection_section',
            'type' => 'content',

            'title' => 'Women Collection',
            'sub_title' => 'Trendy fashion for women',
            'button_text' => 'Shop Now',
            'link_url' => '/women',

            'status' => 'active',
        ]);

        // ================= WATCHES =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'watch_section',
            'type' => 'content',

            'title' => 'Luxury Watches',
            'sub_title' => 'Premium watch collection',
            'button_text' => 'Explore',
            'link_url' => '/watches',

            'status' => 'active',
        ]);

        // ================= TOP SECTION =================
        CMS::create([
            'page' => 'home_page',
            'section' => 'top_section',
            'type' => 'content',

            'title' => 'Welcome to Our Store',
            'sub_title' => 'Best Products in Bangladesh',
            'button_text' => 'Start Shopping',
            'link_url' => '/shop',

            'status' => 'active',
        ]);
    }
}
