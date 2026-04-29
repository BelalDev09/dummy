<?php

namespace App\Http\Controllers\Web\backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Brand::latest();

            return DataTables::of($data)
                ->addIndexColumn()

                // BULK CHECK
                ->addColumn('bulk_check', function ($row) {
                    return '
                    <div class="form-check">
                        <input type="checkbox"
                            class="form-check-input select_data"
                            value="' . $row->id . '"
                            onclick="select_single_item(' . $row->id . ')">
                    </div>
                ';
                })

                ->editColumn('logo', function ($row) {

                    if ($row->logo) {
                        return '
                        <img src="' . asset($row->logo) . '"
                             class="avatar-sm rounded-circle object-fit-cover"
                             alt="Brand Logo">
                    ';
                    }

                    return '<span class="badge bg-secondary-subtle text-secondary">No Logo</span>';
                })

                // NAME
                ->editColumn('name', function ($row) {
                    return '<span class="fw-semibold">' . $row->name . '</span>';
                })

                ->addColumn('status', function ($row) {

                    return '
   <div class="form-check form-switch form-check-success">
        <input class="form-check-input status-switch"
            type="checkbox"
            role="switch"
            onclick="changeStatus(' . $row->id . ')"
            ' . ($row->status ? 'checked' : '') . '>
    </div>
    ';
                })

                ->addColumn('action', function ($row) {

                    return '
                    <div class="d-flex justify-content-end gap-2">

                        <a href="' . route('admin.brands.edit', $row->id) . '"
                           class="btn btn-soft-info btn-sm">
                            <i class="ri-edit-2-line"></i>
                        </a>

                        <button onclick="deleteBrand(' . $row->id . ')"
                            class="btn btn-soft-danger btn-sm">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>

                    </div>
                ';
                })

                ->rawColumns(['bulk_check', 'logo', 'name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.brands.index');
    }

    public function create()
    {
        return view('backend.layout.brands.create');
    }

    public function store(BrandRequest $request)
    {
        $logoPath = null;
        $imagePath = null;
        $bannerPath = null;



        if ($request->hasFile('logo')) {
            $logoPath = Helper::fileUpload(
                $request->file('logo'),
                'brands',
                Str::slug($request->name) . '_logo'
            );
        }
        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'brands',
                Str::slug($request->name) . '_image'
            );
        }

        if ($request->hasFile('banner')) {
            $bannerPath = Helper::fileUpload(
                $request->file('banner'),
                'brands',
                Str::slug($request->name) . '_banner'
            );
        }
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logoPath,
            'image' => $imagePath,
            'banner' => $bannerPath,
            'description' => $request->description,
            'country' => $request->country,
            'website' => $request->website,
            'status' => true,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.layout.brands.edit', compact('brand'));
    }

    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $logo = $brand->logo;
        $image = $brand->image;
        $banner = $brand->banner;

        if ($request->file('logo') && $request->file('logo')->isValid()) {
            $logo = Helper::fileUpload($request->file('logo'), 'brands', Str::slug($request->name) . '_logo');
            if ($brand->logo) {
                Helper::deleteFile($brand->logo);
            }
        }

        if ($request->file('image') && $request->file('image')->isValid()) {
            $image = Helper::fileUpload($request->file('image'), 'brands', Str::slug($request->name) . '_image');
            if ($brand->image) {
                Helper::deleteFile($brand->image);
            }
        }

        if ($request->file('banner') && $request->file('banner')->isValid()) {
            $banner = Helper::fileUpload($request->file('banner'), 'brands', Str::slug($request->name) . '_banner');
            if ($brand->banner) {
                Helper::deleteFile($brand->banner);
            }
        }

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'logo' => $logo,
            'image' => $image,
            'banner' => $banner,
            'description' => $request->description,
            'country' => $request->country,
            'website' => $request->website,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted'
        ]);
    }

    public function changeStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->status = !$brand->status;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected'
            ]);
        }

        $brands = Brand::whereIn('id', $ids)->get();

        foreach ($brands as $brand) {

            if ($brand->logo) {
                Helper::deleteFile($brand->logo);
            }

            if ($brand->image) {
                Helper::deleteFile($brand->image);
            }

            if ($brand->banner) {
                Helper::deleteFile($brand->banner);
            }

            $brand->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Selected brands deleted successfully'
        ]);
    }
}
