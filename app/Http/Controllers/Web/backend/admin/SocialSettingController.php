<?php

namespace App\Http\Controllers\Web\backend\admin;

use App\Http\Controllers\Controller;
use App\Models\SocialSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SocialSettingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SocialSetting::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="' . asset("storage/" . $row->image) . '" width="40" height="40" style="border-radius:50%">'
                        : '<span class="text-muted">No Image</span>';
                })

                ->addColumn('status', function ($row) {
                    return '
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input status-toggle"
                                type="checkbox"
                                data-id="' . $row->id . '"
                                ' . ($row->status == "active" ? "checked" : "") . '>
                        </div>
                    ';
                })

                ->addColumn('action', function ($row) {

                    $edit = route('admin.social-settings.edit', $row->id);

                    return '
        <div class="d-flex gap-2">

            <a href="' . $edit . '"
               class="btn btn-sm btn-soft-primary btn-icon"
               title="Edit">
                <i class="ri-edit-2-line"></i>
            </a>

        </div>
    ';
                })

                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layout.social_setting.index');
    }

    public function create()
    {
        return view('backend.layout.social_setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string|max:255',
            'link'   => 'required|url|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ], [
            'title.required' => 'The title field is required.',
            'title.string'   => 'The title must be a valid string.',
            'title.max'      => 'The title may not be greater than 255 characters.',

            'link.required'  => 'The link field is required.',
            'link.url'       => 'Please provide a valid URL (must include http or https).',
            'link.max'       => 'The link may not be greater than 255 characters.',

            'image.image'    => 'The file must be an image.',
            'image.mimes'    => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'image.max'      => 'The image size may not be greater than 5MB.',
        ]);

        $socialSetting = new SocialSetting();
        $socialSetting->title = $request->title;
        $socialSetting->link = $request->link;

        // default status safe
        $socialSetting->status = $request->status ?? 'inactive';

        if ($request->hasFile('image')) {
            $socialSetting->image = $request->file('image')->store('social_icons', 'public');
        }

        $socialSetting->save();

        return redirect()
            ->route('admin.social-settings.index')
            ->with('success', 'Social setting created successfully.');
    }

    public function edit($id)
    {
        $socialSetting = SocialSetting::findOrFail($id);
        return view('backend.layout.social_setting.edit', compact('socialSetting'));
    }

    public function update(Request $request, $id)
    {
        $socialSetting = SocialSetting::findOrFail($id);

        $request->validate([
            'title'  => 'required|string|max:255',
            'link'   => 'required|url|max:255',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $socialSetting->title = $request->title;
        $socialSetting->link = $request->link;

        // switch logic safe
        $socialSetting->status = $request->has('status') ? 'active' : 'inactive';

        // image update (safe & clean)
        if ($request->hasFile('image')) {

            // old image delete
            if ($socialSetting->image && file_exists(storage_path('app/public/' . $socialSetting->image))) {
                unlink(storage_path('app/public/' . $socialSetting->image));
            }

            $socialSetting->image = $request->file('image')->store('social_icons', 'public');
        }

        $socialSetting->save();

        return redirect()
            ->route('admin.social-settings.index')
            ->with('success', 'Social setting update successfully.');
    }

    public function changeStatus($id)
    {
        $data = SocialSetting::findOrFail($id);

        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'status' => $data->status,
            'message' => 'Status updated successfully',
        ]);
    }
}
