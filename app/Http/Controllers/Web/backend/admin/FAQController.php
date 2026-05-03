<?php

namespace App\Http\Controllers\Web\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class FAQController  extends Controller
{
    /* ---------------------------------
        INDEX
    ----------------------------------*/
    public function index()
    {
        return view('backend.layout.faq.index');
    }

    /* ---------------------------------
        DATATABLE LIST
    ----------------------------------*/
    public function list(Request $request)
    {
        if ($request->ajax()) {

            $data = FAQ::select(['id', 'que', 'ans', 'status', 'created_at'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('question', function ($row) {
                    return '<div class="fw-semibold">' . $row->que . '</div>';
                })

                ->addColumn('answer', function ($row) {
                    return '<span class="text-muted">' . Str::limit($row->ans, 60) . '</span>';
                })

                ->addColumn('status', function ($row) {
                    $class = $row->status === 'active'
                        ? 'bg-success-subtle text-success'
                        : 'bg-secondary-subtle text-secondary';

                    return '<span class="badge rounded-pill ' . $class . '">' . ucfirst($row->status) . '</span>';
                })

                ->addColumn('created', function ($row) {
                    return '<span class="text-muted small">' . $row->created_at->format("d M Y") . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.faq.show', $row->id) . '" class="btn btn-sm btn-light">
                            <i class="ri-eye-line"></i>
                        </a>

                        <a href="' . route('admin.faq.edit', $row->id) . '" class="btn btn-sm btn-info">
                            <i class="ri-edit-2-line"></i>
                        </a>

                    ';
                })

                ->rawColumns(['question', 'answer', 'status', 'action'])
                ->make(true);
        }

        return abort(404);
    }

    public function create()
    {
        // $faq = FAQ::findOrFail($id);
        return view('backend.layout.faq.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'que' => 'required|string',
            'ans' => 'required|string',
        ]);

        FAQ::create([
            'que' => $request->que,
            'ans' => $request->ans,
            'status' => 'active',
        ]);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ created successfully');
    }
    public function show($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.layout.faq.show', compact('faq'));
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.layout.faq.edit', compact('faq'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'que' => 'required|max:255',
            'ans' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $faq = FAQ::findOrFail($id);

        $faq->update([
            'que' => $request->que,
            'ans' => $request->ans,
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ Updated Successfully');
    }


    public function status(Request $request)
    {
        $faq = FAQ::find($request->id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'Not found'
            ]);
        }

        $faq->update([
            'status' => $faq->status === 'active' ? 'inactive' : 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status Updated'
        ]);
    }
    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully');
    }
}
