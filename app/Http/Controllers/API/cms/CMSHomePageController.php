<?php

namespace App\Http\Controllers\API\cms;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CMS\Home\CategorySectionResource;
use App\Http\Resources\API\V1\CMS\Home\HighTechCollectionSectionResource;
use App\Http\Resources\API\V1\CMS\Home\MenCollectionSectionResource;
use App\Http\Resources\API\V1\CMS\Home\TopSectionResource;
use App\Http\Resources\API\V1\CMS\Home\WatchesCollectionSectionResource;
use App\Http\Resources\API\V1\CMS\Home\WomenCollectionSectionResource;
use App\Models\Category;
use App\Models\CMS;
use App\Models\SubCategory;
use App\Services\HomeSectionProductService;
use App\Traits\apiresponse;
use Exception;

class CMSHomePageController extends Controller
{
    use apiresponse;

    protected HomeSectionProductService $productService;

    public function __construct(HomeSectionProductService $productService)
    {
        $this->productService = $productService;
    }

    // Home Page Top Section

    public function topSection()
    {
        try {
            $topSection = CMS::where('page', 'home_page')
                ->where('section', 'top_section')
                ->first();

            if (!$topSection) {
                return $this->error([], 'Top Section Data Not Found!', 404);
            }

            return $this->success(
                new TopSectionResource($topSection),
                'Home Page Top Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    // Category Section

    public function categorySection()
    {
        try {
            $categorySection = CMS::where('page', 'home_page')
                ->where('section', 'category_section')
                ->first();

            if (!$categorySection) {
                return $this->error([], 'Category Section Data Not Found!', 404);
            }

            return $this->success(
                new CategorySectionResource($categorySection),
                'Home Page Category Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }
    // Men collection section
    public function menCollectionSection()
    {
        try {
            $menCollection = CMS::where('page', 'home_page')
                ->where('section', 'men_collection_section')
                ->first();

            if (!$menCollection) {
                return $this->error([], 'Men Collection Section Data Not Found!', 404);
            }

            $menCategoryId = Category::where('slug', 'men')->value('id');

            if (!$menCategoryId) {
                return $this->error([], 'Men Category Not Found!', 404);
            }

            $products = $this->productService->getProducts(
                categoryIds: [$menCategoryId],
                subCategorySlugs: request('sub_category') ? [request('sub_category')] : [],
                filters: request()->only(['brand_id', 'min_price', 'max_price', 'sort']),
                perPage: (int) request('per_page', 8),
                paginate: true
            );

            $menCollection->products       = $products->items();
            $menCollection->pagination     = $this->buildPaginationMeta($products);

            return $this->success(
                new MenCollectionSectionResource($menCollection),
                'Home Page Men Collection Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    // women collection section
    public function womenCollectionSection()
    {
        try {
            $womenCollection = CMS::where('page', 'home_page')
                ->where('section', 'women_collection_section')
                ->first();

            if (!$womenCollection) {
                return $this->error([], 'Women Collection Section Data Not Found!', 404);
            }

            $womenCategoryId = Category::where('slug', 'women')->value('id');

            if (!$womenCategoryId) {
                return $this->error([], 'Women Category Not Found!', 404);
            }

            $products = $this->productService->getProducts(
                categoryIds: [$womenCategoryId],
                subCategorySlugs: request('sub_category') ? [request('sub_category')] : [],
                filters: request()->only(['brand_id', 'min_price', 'max_price', 'sort']),
                perPage: (int) request('per_page', 8),
                paginate: true
            );

            $womenCollection->products   = $products->items();
            $womenCollection->pagination = $this->buildPaginationMeta($products);

            return $this->success(
                new WomenCollectionSectionResource($womenCollection),
                'Home Page Women Collection Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    //   watches section collection
    public function watchesCollectionSection()
    {
        try {
            $watchesCollection = CMS::where('page', 'home_page')
                ->where('section', 'watch_section')
                ->where('status', 1)
                ->first();

            if (!$watchesCollection) {
                return $this->error([], 'Watches Collection Section Data Not Found!', 404);
            }

            $categoryIds = Category::whereIn('slug', ['men', 'women'])->pluck('id')->toArray();


            $subCategorySlugs = request('sub_category')
                ? [request('sub_category')]
                : ['men-watches', 'women-watches'];

            $watchSubCategoryIds = SubCategory::whereIn('slug', $subCategorySlugs)->pluck('id');

            if ($watchSubCategoryIds->isEmpty()) {
                return $this->error([], 'Watches SubCategory Not Found!', 404);
            }

            $products = $this->productService->getProducts(
                categoryIds: $categoryIds,
                subCategorySlugs: $subCategorySlugs,
                filters: request()->only(['brand_id', 'min_price', 'max_price', 'sort']),
                perPage: (int) request('per_page', 8),
                paginate: true
            );

            $watchesCollection->products   = $products->items();
            $watchesCollection->pagination = $this->buildPaginationMeta($products);

            return $this->success(
                new WatchesCollectionSectionResource($watchesCollection),
                'Home Page Watches Collection Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    // High Tech Collection Section

    public function highTechCollectionSection()
    {
        try {
            $highTechCollection = CMS::where('page', 'home_page')
                ->where('section', 'high_tech_section')
                ->where('status', 1)
                ->first();

            if (!$highTechCollection) {
                return $this->error([], 'High Tech Collection Section Data Not Found!', 404);
            }

            $categoryIds = Category::whereIn('slug', ['men', 'women'])->pluck('id')->toArray();

            $subCategorySlugs = request('sub_category')
                ? [request('sub_category')]
                : ['men-high-tech', 'women-high-tech'];

            $highTechSubCategoryIds = SubCategory::whereIn('slug', $subCategorySlugs)->pluck('id');

            if ($highTechSubCategoryIds->isEmpty()) {
                return $this->error([], 'High Tech SubCategory Not Found!', 404);
            }

            $products = $this->productService->getProducts(
                categoryIds: $categoryIds,
                subCategorySlugs: $subCategorySlugs,
                filters: request()->only(['brand_id', 'min_price', 'max_price', 'sort']),
                perPage: (int) request('per_page', 8),
                paginate: true
            );

            $highTechCollection->products   = $products->items();
            $highTechCollection->pagination = $this->buildPaginationMeta($products);

            return $this->success(
                new HighTechCollectionSectionResource($highTechCollection),
                'Home Page High Tech Collection Section Data Retrieved Successfully!',
                200
            );
        } catch (Exception $e) {
            return $this->error([], 'An error occurred: ' . $e->getMessage(), 500);
        }
    }

    // Helper

    private function buildPaginationMeta($paginator): array
    {
        return [
            'total'        => $paginator->total(),
            'per_page'     => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'from'         => $paginator->firstItem(),
            'to'           => $paginator->lastItem(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
        ];
    }
}
