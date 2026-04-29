<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use apiresponse;

    /**
     * Orders list
     */
    public function index(Request $request)
    {
        try {

            $query = Order::with('user')->latest();

            if ($request->filled('order_status')) {
                $query->where('order_status', $request->order_status);
            }

            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }

            $orders = $query->get();

            return $this->success(
                OrderResource::collection($orders),
                'Orders fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Single order
     */
    public function show($id)
    {
        try {

            $order = Order::with('user')->findOrFail($id);

            return $this->success(
                new OrderResource($order),
                'Order details fetched successfully',
                200
            );
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
