<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use apiresponse;


    // ALL CATEGORIES

    public function index(Request $request)
    {
        try {
            $query = Category::query()
                ->where('status', 1);

            // slug
            if ($request->filled('slug')) {
                $slugs = is_array($request->slug)
                    ? $request->slug
                    : explode(',', $request->slug);

                $query->whereIn('slug', $slugs);
            }

            $categories = $query
                ->select('id', 'name', 'slug', 'image')
                ->orderBy('id', 'asc')
                ->get();

            return $this->success(
                $categories,
                'Categories fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error([], 'Something went wrong', 500);
        }
    }

    // SUB CATEGORIES
    public function subCategories(Request $request)
    {
        try {

            $query = SubCategory::query()
                ->with('category:id,name,slug')
                ->where('status', 1);
            // filtering category
            if ($request->filled('category')) {

                $query->whereHas('category', function ($q) use ($request) {
                    $q->where(function ($sub) use ($request) {
                        $sub->where('name', 'like', '%' . $request->category . '%')
                            ->orWhere('slug', 'like', '%' . $request->category . '%');
                    });
                });
            }

            // filtering sub_category
            if ($request->filled('sub_category')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->sub_category . '%')
                        ->orWhere('slug', 'like', '%' . $request->sub_category . '%');
                });
            }

            $subCategories = $query->get();

            //  grouping map
            $data = $subCategories
                ->groupBy('category_id')
                ->map(function ($items) {

                    $category = $items->first()->category;

                    return [
                        'category' => [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ],

                        'sub_categories' => $items->map(function ($sub) {
                            return [
                                'id' => $sub->id,
                                'name' => $sub->name,
                                'slug' => $sub->slug,
                                'image' => $sub->image ? asset($sub->image) : null,
                            ];
                        })->values()
                    ];
                })
                ->values();

            return $this->success(
                $data,
                'Sub categories fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error([], 'Something went wrong', 500);
        }
    }
}
