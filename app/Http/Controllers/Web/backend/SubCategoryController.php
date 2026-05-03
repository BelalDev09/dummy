<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SubCategory::with('category')->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                // Bulk checkbox (Velzon style)
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

                // Category
                ->addColumn('category', function ($row) {
                    return '<span class="badge bg-soft-primary text-primary">
                            ' . ($row->category?->name ?? '-') . '
                        </span>';
                })

                ->addColumn('image', function ($row) {
                    $imageUrl = filter_var($row->image, FILTER_VALIDATE_URL)
                        ? $row->image
                        : asset($row->image);

                    return $row->image
                        ? '<img src="' . $imageUrl . '"
                        class="rounded avatar-sm object-fit-cover"
                        style="width:50px;height:50px;">'
                        : '<span class="badge bg-soft-secondary text-dark">No Image</span>';
                })

                ->addColumn('status', function ($row) {
                    return '
                    <div class="form-check form-switch form-switch-success">
                        <input type="checkbox"
                            class="form-check-input"
                            onclick="changeStatus(' . $row->id . ')"
                            ' . ($row->status ? 'checked' : '') . '>
                    </div>
                ';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex gap-2">

                        <a href="' . route('admin.sub-categories.edit', $row->id) . '"
                           class="btn btn-sm btn-soft-primary">
                            <i class="ri-edit-2-line"></i>
                        </a>

                        <button onclick="showDeleteAlert(' . $row->id . ')"
                            class="btn btn-sm btn-soft-danger">
                            <i class="ri-delete-bin-line"></i>
                        </button>

                    </div>
                ';
                })

                ->rawColumns(['bulk_check', 'category', 'image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.subcategories.index');
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('backend.layout.subcategories.create', compact('categories'));
    }

    public function store(SubCategoryRequest $request)
    {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'sub-categories',
                $request->name
            );
        }

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => 1,
        ]);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'SubCategory created successfully');
    }

    public function edit($id)
    {
        $data = SubCategory::findOrFail($id);
        $categories = Category::where('status', 1)->get();

        return view('backend.layout.subcategories.edit', compact('data', 'categories'));
    }

    public function update(SubCategoryRequest $request, $id)
    {
        $sub = SubCategory::findOrFail($id);

        $imagePath = $sub->image;

        if ($request->file('image') && $request->file('image')->isValid()) {

            // upload new image first
            $imagePath = Helper::fileUpload(
                $request->file('image'),
                'sub-categories',
                $request->name
            );

            // delete old image after success
            if ($sub->image) {
                Helper::deleteFile($sub->image);
            }
        }

        $sub->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'SubCategory updated successfully');
    }
    public function destroy($id)
    {
        $sub = SubCategory::findOrFail($id);

        if ($sub->image) {
            Helper::deleteFile($sub->image);
        }

        $sub->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    public function changeStatus($id)
    {
        $sub = SubCategory::findOrFail($id);

        $sub->status = !$sub->status;
        $sub->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = (array) $request->ids;

        /** @var \Illuminate\Database\Eloquent\Collection<\App\Models\SubCategory> $subs */
        $subs = SubCategory::whereIn('id', $ids)->get();

        foreach ($subs as $sub) {
            if ($sub->image) {
                Helper::deleteFile($sub->image);
            }

            $sub->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk deleted successfully'
        ]);
    }
}
