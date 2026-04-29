<?php

namespace App\Http\Controllers\Web\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\SubCategory;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'subCategory', 'brand'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('bulk_check', function ($row) {
                    return '
                    <div class="form-check">
                        <input type="checkbox"
                            class="form-check-input select_data"
                            value="' . $row->id . '"
                            onclick="select_single_item(' . $row->id . ')">
                    </div>';
                })
                ->addColumn('image', function ($row) {
                    $img = $this->productService->getThumbnailHtml($row);
                    return '
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-light rounded">' . $img . '</div>
                            </div>
                        </div>
                    </div>';
                })
                ->addColumn('category', function ($row) {
                    return '<span class="badge bg-primary-subtle text-primary">'
                        . ($row->category?->name ?? '-') . '</span>';
                })
                ->addColumn('sub_category', function ($row) {
                    return '<span class="badge bg-info-subtle text-info">'
                        . ($row->subCategory?->name ?? '-') . '</span>';
                })
                ->addColumn('brand', function ($row) {
                    return '<span class="badge bg-dark-subtle text-dark">'
                        . ($row->brand?->name ?? '-') . '</span>';
                })
                ->addColumn('price', function ($row) {
                    return '<span class="fw-semibold text-success">$ '
                        . number_format($row->price, 2) . '</span>';
                })
                ->addColumn('stock', function ($row) {
                    if ($row->stock > 10)
                        return '<span class="badge bg-success-subtle text-success">In Stock</span>';
                    elseif ($row->stock > 0)
                        return '<span class="badge bg-warning-subtle text-warning">Low Stock (' . $row->stock . ')</span>';
                    return '<span class="badge bg-danger-subtle text-danger">Out of Stock</span>';
                })
                ->addColumn('status', function ($row) {
                    return '
                 <div class="form-check form-switch form-check-success">
                        <input class="form-check-input" type="checkbox"
                            onclick="changeStatus(' . $row->id . ')"
                            ' . ($row->status ? 'checked' : '') . '>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    return '
    <div class="d-flex justify-content-end gap-2">

        <a href="' . route('admin.products.show', $row->id) . '"
           class="btn btn-soft-primary btn-sm"
           title="View Product">
            <i class="ri-eye-line"></i>
        </a>

        <a href="' . route('admin.products.edit', $row->id) . '"
           class="btn btn-soft-info btn-sm"
           title="Edit Product">
            <i class="ri-edit-2-line"></i>
        </a>

        <button onclick="deleteProduct(' . $row->id . ')"
            class="btn btn-soft-danger btn-sm"
            title="Delete Product">
            <i class="ri-delete-bin-6-line"></i>
        </button>

    </div>';
                })
                ->rawColumns(['bulk_check', 'image', 'category', 'sub_category', 'brand', 'price', 'stock', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.products.index');
    }

    public function create()
    {
        $data = $this->productService->getDataForCreate();
        return view('backend.layout.products.create', $data);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'subCategory', 'brand', 'variants'])->findOrFail($id);

        $gallery = $product->gallery ?? [];

        return view('backend.layout.products.show', compact('product', 'gallery'));
    }

    public function store(ProductRequest $request)
    {
        try {
            //  Validate gallery images specifically
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $img) {
                    if (!in_array($img->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Gallery contains an unsupported file type: "' . $img->getClientOriginalName() . '". Only JPG, PNG, WebP are allowed.');
                    }
                    if ($img->getSize() > 2 * 1024 * 1024) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', '"' . $img->getClientOriginalName() . '" is too large (' . round($img->getSize() / 1024 / 1024, 1) . 'MB). Max allowed size is 2MB per image.');
                    }
                }
            }

            //  Validate thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                if (!in_array($thumb->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Thumbnail has an unsupported file type. Only JPG, PNG, WebP are allowed.');
                }
                if ($thumb->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Thumbnail is too large (' . round($thumb->getSize() / 1024 / 1024, 1) . 'MB). Max allowed size is 2MB.');
                }
            }

            $this->productService->store($request);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product "' . $request->name . '" created successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Product store DB error: ' . $e->getMessage());
            $msg = str_contains($e->getMessage(), 'Duplicate entry')
                ? 'A product with this SKU already exists. Please use a unique SKU.'
                : 'Database error while saving product. Please try again.';
            return redirect()->back()->withInput()->with('error', $msg);
        } catch (\Exception $e) {
            Log::error('Product store error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $data = $this->productService->getDataForEdit($product);
        return view('backend.layout.products.edit', $data);
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            //  Validate gallery images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $img) {
                    if (!in_array($img->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', '"' . $img->getClientOriginalName() . '" is not a supported image type.');
                    }
                    if ($img->getSize() > 2 * 1024 * 1024) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', '"' . $img->getClientOriginalName() . '" exceeds 2MB size limit.');
                    }
                }
            }

            //  Validate thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumb = $request->file('thumbnail');
                if (!in_array($thumb->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Thumbnail has an unsupported file type.');
                }
                if ($thumb->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Thumbnail exceeds 2MB size limit.');
                }
            }

            //  Run service update

            $this->productService->update($request, $product);

            $product->refresh();

            $gallery = is_string($product->gallery)
                ? (json_decode($product->gallery, true) ?? [])
                : ($product->gallery ?? []);

            if ($request->has('delete_gallery')) {
                foreach ($request->delete_gallery as $path) {
                    $storagePath = str_replace('storage/', '', $path);
                    if (Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
                    }
                    $gallery = array_values(array_filter($gallery, fn($p) => $p !== $path));
                }
            }

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path      = $file->store('products/gallery', 'public');
                    $gallery[] = 'storage/' . $path;
                }
            }

            $product->gallery = $gallery;
            $product->save();

            //  Add new gallery images
            if ($request->hasFile('gallery')) {
                $existing = is_string($product->fresh()->gallery)
                    ? (json_decode($product->fresh()->gallery, true) ?? [])
                    : ($product->fresh()->gallery ?? []);

                foreach ($request->file('gallery') as $file) {
                    $path       = $file->store('products/gallery', 'public');
                    $existing[] = 'storage/' . $path;
                }

                $product->gallery = $existing;
                $product->save();
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Product "' . $product->name . '" updated successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Product update DB error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database error while updating product. Please try again.');
        } catch (\Exception $e) {
            Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->thumbnail) {
                \App\Helper\Helper::deleteFile($product->thumbnail);
            }
            $product->delete();
            return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Product delete failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Delete failed'], 500);
        }
    }

    public function changeStatus(Product $product)
    {
        $product->update(['status' => !$product->status]);
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function getSubCategories(Request $request)
    {
        $request->validate(['category_id' => 'required|integer|exists:categories,id']);
        try {
            $subCategories = SubCategory::where('category_id', $request->category_id)
                ->select('id', 'name')->get();
            return response()->json($subCategories);
        } catch (\Throwable $e) {
            Log::error('SubCategory fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:products,id']);
        Product::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected products deleted successfully']);
    }
}
