<?php

namespace App\Http\Controllers\Web\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Yajra\DataTables\Facades\DataTables;

class NewslettersController extends Controller
{

    public function index()
    {
        return view('backend.layout.newsletters.index');
    }

    // AJAX DATA FOR DATATABLE
    public function getData(Request $request)
    {
        if ($request->ajax()) {

            $data = Newsletter::latest()->select(['id', 'email', 'status', 'created_at']);

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d M, Y');
                })

                // ->addColumn('action', function ($row) {
                //     return '
                //         <form action="' . route('admin.newsletters.destroy', $row->id) . '" method="POST">
                //             ' . csrf_field() . '
                //             ' . method_field("DELETE") . '
                //             <button class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">
                //                 Delete
                //             </button>
                //         </form>
                //     ';
                // })

                ->rawColumns(['status'])
                ->make(true);
        }
    }

    // DELETE
    public function destroy($id)
    {
        Newsletter::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
