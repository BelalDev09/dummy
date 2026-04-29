<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductService
{
    public function getDataForCreate()
    {
        return [
            'categories' => Category::where('status', true)->get(),
            'brands'     => Brand::where('status', true)->get(),
        ];
    }

    public function getDataForEdit(Product $product)
    {
        return [
            'product'       => $product->load('variants'),
            'categories'    => Category::where('status', true)->get(),
            'brands'        => Brand::where('status', true)->get(),
            'subCategories' => SubCategory::where('category_id', $product->category_id)
                ->where('status', true)
                ->get(),
        ];
    }

    public function store(Request $request): Product
    {
        return $this->save($request);
    }

    public function update(Request $request, Product $product): Product
    {
        return $this->save($request, $product);
    }

    private function save(Request $request, ?Product $product = null): Product
    {
        DB::beginTransaction();

        try {
            $isUpdate = $product !== null;

            // File Uploads
            $thumbnail = $this->handleThumbnail($request, $product);
            $gallery   = $this->handleGallery($request, $product);

            // Variants Processing
            $variants    = $this->parseVariants($request->variants ?? []);
            $hasVariants = count($variants) > 0;
            $totalStock  = $hasVariants
                ? collect($variants)->sum('stock')
                : (int) $request->stock;

            // Prepare Product Data
            $data = $this->prepareProductData($request, $totalStock, $thumbnail, $gallery, $isUpdate);

            if ($isUpdate) {
                $product->update($data);
                $product->variants()->delete();
            } else {
                $product = Product::create($data);
            }

            // Save Variants if any
            if ($hasVariants) {
                $this->saveVariants($product->id, $variants);
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function handleThumbnail(Request $request, ?Product $product = null): ?string
    {
        if ($request->hasFile('thumbnail')) {
            return Helper::fileUpload(
                $request->file('thumbnail'),
                'products',
                Str::slug($request->name) . '_thumb'
            );
        }

        return $product?->thumbnail;
    }

    private function handleGallery(Request $request, ?Product $product = null): ?array
    {
        if ($request->hasFile('gallery')) {
            $gallery = [];

            foreach ($request->file('gallery') as $index => $file) {
                $gallery[] = Helper::fileUpload(
                    $file,
                    'products/gallery',
                    Str::slug($request->name) . '_gallery_' . $index
                );
            }

            return $gallery;
        }

        return $product
            ? $this->normalizeGallery($product->gallery)
            : [];
    }
    private function normalizeGallery($gallery): array
    {
        if (empty($gallery)) {
            return [];
        }

        if (is_array($gallery)) {
            return $gallery;
        }

        return json_decode($gallery, true) ?? [];
    }
    private function prepareProductData(
        Request $request,
        int $totalStock,
        ?string $thumbnail,
        ?array $gallery,
        bool $isUpdate
    ): array {
        return [
            'category_id'       => $request->category_id,
            'sub_category_id'   => $request->sub_category_id,
            'brand_id'          => $request->brand_id,
            'name'              => $request->name,
            'slug'              => $this->generateUniqueSlug($request->name, $isUpdate ? $request->product->id : null),
            'sku'               => $request->sku ?? ($isUpdate ? null : $this->generateSku()),
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'price'             => $request->price,
            'discount_price'    => $request->discount_price,
            'stock'             => $totalStock,
            'thumbnail'         => $thumbnail,
            'gallery'           => !empty($gallery) ? $gallery : null,
            'material'          => $request->material,
            'weight'            => $request->weight,
            'dimensions'        => $request->dimensions,
            'tags'              => $request->tags,
            'is_featured'       => $request->boolean('is_featured'),
            'status'            => 1,
        ];
    }

    private function parseVariants(array $rawVariants): array
    {
        return collect($rawVariants)
            ->filter(fn($v) => !empty(trim($v['size'] ?? '')) || !empty(trim($v['color'] ?? '')))
            ->map(fn($v) => [
                'size'       => trim($v['size'] ?? '') ?: null,
                'color'      => trim($v['color'] ?? '') ?: null,
                'color_hex'  => trim($v['color_hex'] ?? '') ?: null,
                'price'      => is_numeric($v['price'] ?? null) ? (float) $v['price'] : null,
                'stock'      => (int) ($v['stock'] ?? 0),
                'sku'        => $this->generateSku(),
                'status'     => true,
            ])
            ->values()
            ->toArray();
    }

    private function saveVariants(int $productId, array $variants): void
    {
        $rows = collect($variants)->map(fn($v) => array_merge($v, [
            'product_id' => $productId,
            'created_at' => now(),
            'updated_at' => now(),
        ]))->toArray();

        ProductVariant::insert($rows);
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $count = 2;

        while (Product::where('slug', $slug . ($count > 2 ? '-' . ($count - 1) : ''))
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $count++;
        }

        return $count > 2 ? $slug . '-' . ($count - 2) : $slug;
    }

    private function generateSku(): string
    {
        do {
            $sku = 'SKU-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists() || ProductVariant::where('sku', $sku)->exists());

        return $sku;
    }

    // DataTable Helper
    public function getThumbnailHtml($row): string
    {
        return $row->thumbnail
            ? '<img src="' . asset($row->thumbnail) . '" width="60" style="border-radius:6px;">'
            : '<span class="badge bg-secondary">No Image</span>';
    }

    public function getStockHtml($row): string
    {
        $hasVariants = $row->variants()->exists();
        $stock = $hasVariants ? $row->variants()->sum('stock') : $row->stock;
        $color = $stock > 10 ? 'success' : ($stock > 0 ? 'warning' : 'danger');
        $label = $hasVariants ? "$stock (variants)" : $stock;

        return "<span class='badge bg-$color'>$label</span>";
    }

    public function getStatusHtml($row): string
    {
        return '
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox"
                    onclick="changeStatus(' . $row->id . ')"
                    ' . ($row->status ? 'checked' : '') . '>
            </div>';
    }

    public function getActionHtml($row): string
    {
        return '
            <a href="' . route('admin.products.edit', $row->id) . '" class="btn btn-sm btn-primary">
               <i class="ri-edit-2-line"></i> Edit
            </a>
            <button onclick="deleteProduct(' . $row->id . ')" class="btn btn-sm btn-danger">
                <i class="ri-delete-bin-line"></i> Delete
            </button>';
    }
}
