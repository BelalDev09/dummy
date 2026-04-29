<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use App\Http\Resources\RelatedProductResource;

class ProductController extends Controller
{
    use apiresponse;
    // ALL PRODUCTS
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'subCategory', 'brand', 'variants'])
                ->where('status', 1);

            // NAME
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            // CATEGORY
            if ($request->filled('category')) {
                $value = $request->category;

                $query->whereHas('category', function ($q) use ($value) {
                    if (is_numeric($value)) {
                        $q->where('id', $value);
                    } else {
                        $q->where('slug', $value)
                            ->orWhere('name', 'like', "%{$value}%");
                    }
                });
            }

            // SUB CATEGORY
            if ($request->filled('sub_category')) {
                $value = $request->sub_category;

                $query->whereHas('subCategory', function ($q) use ($value) {
                    if (is_numeric($value)) {
                        $q->where('id', $value);
                    } else {
                        $q->where('slug', $value)
                            ->orWhere('name', 'like', "%{$value}%");
                    }
                });
            }

            // BRAND
            if ($request->filled('brand')) {
                $value = $request->brand;

                $query->whereHas('brand', function ($q) use ($value) {
                    if (is_numeric($value)) {
                        $q->where('id', $value);
                    } else {
                        $q->where('slug', $value)
                            ->orWhere('name', 'like', "%{$value}%");
                    }
                });
            }

            // TAG
            if ($request->filled('tag')) {
                $query->whereJsonContains('tags', $request->tag);
            }

            // COLOR
            if ($request->filled('color')) {
                $query->whereHas('variants', function ($q) use ($request) {
                    $q->where('color', $request->color);
                });
            }

            // SIZE
            if ($request->filled('size')) {
                $query->whereHas('variants', function ($q) use ($request) {
                    $q->where('size', $request->size);
                });
            }

            $products = $query->latest()
                ->paginate($request->limit ?? 12);

            return $this->success(
                ProductResource::collection($products)->response()->getData(true),
                'Products fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error([], 'Failed to fetch products', 500);
        }
    }

    // SINGLE PRODUCT
    // public function show($id)
    // {
    //     try {
    //         $product = Product::with(['category', 'subCategory', 'brand', 'variants'])
    //             ->where('id', $id)
    //             ->where('status', 1)
    //             ->first();

    //         if (!$product) {
    //             return $this->error('Product not found', 404);
    //         }

    //         // RELATED PRODUCTS

    //         $relatedProducts = Product::with(['category', 'subCategory', 'brand', 'variants'])
    //             ->where('status', 1)
    //             ->where('id', '!=', $product->id)
    //             ->where(function ($q) use ($product) {
    //                 $q->where('sub_category_id', $product->sub_category_id)
    //                     ->where('category_id', $product->category_id);
    //             })
    //             ->latest()
    //             ->take(8)
    //             ->get();

    //         // for mixed band,tag,category,sub-category related products
    //         //                 ->where('id', '!=', $product->id)
    //         // ->where(function ($q) use ($product) {
    //         //     $q->where('sub_category_id', $product->sub_category_id)
    //         //       ->orWhere('brand_id', $product->brand_id)
    //         //       ->orWhereJsonContains('tags', $product->tags[0] ?? null);
    //         // })

    //         return $this->success([
    //             'product' => new ProductResource($product),
    //             'related_products' => ProductResource::collection($relatedProducts)
    //         ], 'Product fetched successfully', 200);
    //     } catch (\Exception $e) {
    //         return $this->error('Failed to fetch product', 500);
    //     }
    // }


    public function show($id, Request $request)
    {
        try {
            $product = Product::with(['category', 'subCategory', 'brand', 'variants'])
                ->where('id', $id)
                ->where('status', 1)
                ->first();

            if (!$product) {
                return $this->error('Product not found', 404);
            }

            // RELATED PRODUCTS
            $relatedProductsQuery = Product::with('brand')
                ->where('status', 1)
                ->where('id', '!=', $product->id)
                ->where(function ($q) use ($product) {
                    $q->where('sub_category_id', $product->sub_category_id)
                        ->where('category_id', $product->category_id);
                });

            $relatedProducts = $relatedProductsQuery
                ->latest()
                ->paginate(4);

            return $this->success([
                'product' => new ProductResource($product),

                'related_products' => RelatedProductResource::collection($relatedProducts)
                    ->response()
                    ->getData(true)

            ], 'Product fetched successfully', 200);
        } catch (\Exception $e) {
            return $this->error('Failed to fetch product', 500);
        }
    }
}
