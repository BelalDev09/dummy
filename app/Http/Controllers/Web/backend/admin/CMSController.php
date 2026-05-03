<?php

namespace App\Http\Controllers\Web\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CMSController extends Controller
{
    /* ================= INDEX ================= */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = CMS::latest();

            if ($request->page) {
                $data->where('page', $request->page);
            }

            if ($request->section) {
                $data->where('section', $request->section);
            }

            if ($request->status) {
                $data->where('status', $request->status);
            }

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn(
                    'page',
                    fn($row) =>
                    '<span class="badge bg-primary">' . e($row->page) . '</span>'
                )

                ->addColumn(
                    'section',
                    fn($row) =>
                    '<span class="badge bg-info">' . e($row->section) . '</span>'
                )

                ->editColumn(
                    'title',
                    fn($row) =>
                    e(Str::limit($row->title, 30))
                )

                ->addColumn('status', function ($row) {

                    $checked = $row->status === 'active' ? 'checked' : '';

                    return '
        <div class="form-check form-switch form-switch-success">
            <input type="checkbox"
                class="form-check-input"
                onclick="changeStatus(' . $row->id . ')"
                ' . $checked . '>
        </div>
    ';
                })
                ->addColumn('action', function ($row) {
                    return '
<div class="d-flex gap-2">

    <a href="' . route('admin.dynamic.cms.edit', $row->id) . '"
       class="btn btn-sm btn-soft-primary btn-icon">
        <i class="ri-edit-2-line"></i>
    </a>

    <button class="btn btn-sm btn-soft-danger btn-icon deleteBtn"
            data-id="' . $row->id . '">
        <i class="ri-delete-bin-line"></i>
    </button>

</div>
';
                })
                ->rawColumns(['page', 'section', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.cms.dynamic.index');
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->file('image'), 'cms/main');
        }

        $v1 = $request->input('v1', []);

        $data['v1'] = $this->handleV1Upload($request, $v1);

        CMS::create($data);

        return redirect()->route('admin.dynamic.cms.index')->with('success', 'CMS Created Successfully');
    }

    //  EDIT
    public function edit($id)
    {
        $cms = CMS::findOrFail($id);
        $cms->v1 = $cms->v1 ?? [];

        return view('backend.layout.cms.dynamic.form', compact('cms'));
    }

    public function create()
    {
        return $this->form();
    }

    public function form($id = null)
    {
        $cms = null;

        if ($id) {
            $cms = CMS::findOrFail($id);
        }

        return view('backend.layout.cms.dynamic.form', compact('cms'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $cms = CMS::findOrFail($id);

        $data = $request->except(['_token', '_method']);

        if ($request->hasFile('image')) {
            $this->deleteFile($cms->image);
            $data['image'] = $this->upload($request->file('image'), 'cms/main');
        }

        $v1 = $request->input('v1', []);

        $data['v1'] = $this->handleV1Upload($request, $v1, $cms->v1 ?? []);

        $cms->update($data);

        return redirect()->route('admin.dynamic.cms.index')->with('success', 'CMS Updated Successfully');
    }

    // DELETE
    public function destroy($id)
    {
        $cms = CMS::findOrFail($id);

        $this->deleteFile($cms->image);

        $v1 = $cms->v1  ?? [];
        foreach ($v1 as $item) {
            if (!empty($item['image'])) {
                $this->deleteFile($item['image']);
            }
        }

        $cms->delete();

        return response()->json(['success' => true]);
    }

    // STATUS
    public function changeStatus($id)
    {
        $cms = CMS::findOrFail($id);

        $cms->status = $cms->status === 'active' ? 'inactive' : 'active';
        $cms->save();

        return response()->json(['success' => true]);
    }

    // UPLOAD
    private function upload($file, $path)
    {
        $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        $file->move(public_path($path), $name);

        return $path . '/' . $name;
    }

    // DELETE FILE
    private function deleteFile($path)
    {
        if ($path && file_exists(public_path($path))) {
            unlink(public_path($path));
        }
    }

    //    V1 IMAGE HANDLER
    private function handleV1Upload($request, $v1, $oldV1 = [])
    {
        $v1Files = $request->file('v1') ?? [];

        foreach ($v1 as $index => &$item) {

            $item['image'] = $oldV1[$index]['image'] ?? null;

            if (isset($v1Files[$index]['image'])) {
                $item['image'] = $this->upload(
                    $v1Files[$index]['image'],
                    'cms/v1'
                );
            }
        }

        return array_values($v1);
    }
    // destroy
    // public function destroy($id)
    // {
    //     $cms = CMS::findOrFail($id);

    //     // delete main image
    //     $this->deleteFile($cms->image);

    //     // delete v1 images if exist
    //     $v1 = $cms->v1 ?? [];

    //     foreach ($v1 as $item) {
    //         if (!empty($item['image'])) {
    //             $this->deleteFile($item['image']);
    //         }
    //     }

    //     $cms->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'CMS deleted successfully'
    //     ]);
    // }
}
