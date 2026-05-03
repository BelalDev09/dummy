<?php

namespace App\Http\Controllers\Web\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Order::with('user')->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                // USER
                ->addColumn('user', function ($row) {
                    $userName = optional($row->user)->name ?: 'N/A';

                    return '
                        <div class="d-flex align-items-center">
                            <div class="avatar-xs me-2">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                    ' . substr($userName, 0, 1) . '
                                </span>
                            </div>
                            <span>' . $userName . '</span>
                        </div>
                    ';
                })

                // ORDER NUMBER
                ->addColumn('order_number', function ($row) {
                    return '<span class="badge bg-dark">#' . $row->order_number . '</span>';
                })

                // AMOUNT
                ->addColumn('amount', function ($row) {
                    return '
                        <span class="fw-bold text-success fs-6">
                            $ ' . number_format($row->total_amount, 2) . '
                        </span>
                    ';
                })

                // PAYMENT STATUS
                ->addColumn('payment_status', function ($row) {
                    $color = match ($row->payment_status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                    };

                    return '<span class="badge bg-' . $color . ' text-uppercase">' . $row->payment_status . '</span>';
                })

                // ORDER STATUS
                ->addColumn('order_status', function ($row) {
                    $color = match ($row->order_status) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    };

                    return '<span class="badge bg-' . $color . ' text-uppercase">' . $row->order_status . '</span>';
                })

                // ACTION
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.orders.show', $row->id) . '"
                           class="btn btn-sm btn-soft-info">
                            <i class="ri-eye-line"></i> View
                        </a>
                    ';
                })

                ->rawColumns(['user', 'order_number', 'amount', 'payment_status', 'order_status', 'action'])
                ->make(true);
        }

        return view('backend.layout.orders.index');
    }
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);

        return view('backend.layout.orders.show', compact('order'));
    }
}
