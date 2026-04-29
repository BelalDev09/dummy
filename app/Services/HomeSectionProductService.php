<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class HomeSectionProductService
{
    /**
     * Fetch products with optional filtering and pagination.
     *
     * @param  array   $categoryIds        Required: category IDs to scope
     * @param  array   $subCategorySlugs   Optional: filter by sub-category slugs
     * @param  array   $filters            Optional: brand_id, min_price, max_price, sort
     *                                       sort values: price_asc | price_desc | newest | oldest
     * @param  int     $perPage            Items per page (default: 8)
     * @param  bool    $paginate           Return paginator (true) or plain collection (false)
     *
     * @return LengthAwarePaginator|Collection
     */
    public function getProducts(
        array  $categoryIds,
        array  $subCategorySlugs = [],
        array  $filters          = [],
        int    $perPage          = 8,
        bool   $paginate         = false
    ): LengthAwarePaginator|Collection {

        $query = Product::with(['brand', 'subCategory', 'wishlists', 'category'])
            ->where('status', 1)
            ->whereIn('category_id', $categoryIds);

        //  Sub-category filter
        if (!empty($subCategorySlugs)) {
            $query->whereHas(
                'subCategory',
                fn($q) =>
                $q->whereIn('slug', $subCategorySlugs)
            );
        }

        //  Brand filter
        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', (int) $filters['brand_id']);
        }

        //  Price range filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        //  Sorting
        match ($filters['sort'] ?? 'newest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'oldest'     => $query->oldest(),
            default      => $query->latest(),
        };

        $attachWishlist = function ($items) {
            return collect($items)->map(function ($product) {
                $product->is_wishlisted = auth()->check()
                    ? $product->wishlists->contains('user_id', auth()->id())
                    : false;
                return $product;
            });
        };

        if ($paginate) {
            $paginator = $query->paginate($perPage);

            $paginator->getCollection()->transform(
                fn($product) => tap($product, function ($p) {
                    $p->is_wishlisted = auth()->check()
                        ? $p->wishlists->contains('user_id', auth()->id())
                        : false;
                })
            );
            return $paginator;
        }

        return $attachWishlist($query->limit($perPage)->get());
    }
}
