<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use apiresponse;
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'product.brand', 'product.category', 'product.subCategory', 'variant'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                })->orWhereHas('product', function ($p) use ($request) {
                    $p->where('name', 'like', "%{$request->search}%");
                });
            });
        }

        $carts = $query->paginate($request->get('per_page', 15));

        return $this->success([
            'message' => 'All carts are Retrived successfully.',
            'carts' => CartResource::collection($carts),
            'stats' => $this->getStats(),
            'pagination' => [
                'total' => $carts->total(),
                'current_page' => $carts->currentPage(),
                'last_page' => $carts->lastPage(),
            ]
        ]);
    }


    private function getStats(): array
    {
        return [
            'total_active'   => Cart::where('status', 'active')->count(),
            'total_value'    => Cart::where('status', 'active')->sum(DB::raw('price * quantity')),
            'unique_users'   => Cart::where('status', 'active')->distinct('user_id')->count(),
            'ordered_today'  => Cart::where('status', 'ordered')
                ->whereDate('updated_at', today())->count(),
        ];
    }

    public function show(Cart $cart)
    {
        $cart->load(['user', 'product.brand', 'product.category', 'product.subCategory', 'variant']);
        if (!$cart) {
            return response()->json(['Cart are not fund.'], 404);
        }

        return $this->success(new CartResource($cart));
    }


    public function updateStatus(Request $request, Cart $cart)
    {
        $request->validate([
            'status' => 'required|in:active,ordered,removed',
        ]);

        $cart->update(['status' => $request->status]);

        return $this->success(
            new CartResource($cart->fresh()),
            'Cart status updated'
        );
    }


    public function destroy(Cart $cart)
    {
        $cart->delete();

        return $this->success(null, 'Cart deleted');
    }


    public function export()
    {
        $carts = Cart::with(['user', 'product', 'variant'])->latest()->get();

        $filename = 'carts_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($carts) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'User Name',
                'User Email',
                'Product Name',
                'Variant',
                'Quantity',
                'Unit Price ($)',
                'Total ($)',
                'Status',
                'Date'
            ]);

            foreach ($carts as $c) {
                fputcsv($handle, [
                    $c->id,
                    $c->user?->name ?? 'Guest',
                    $c->user?->email ?? '-',
                    $c->product?->name ?? '-',
                    $c->variant?->name ?? 'Default',
                    $c->quantity,
                    number_format($c->price, 2),
                    number_format($c->price * $c->quantity, 2),
                    ucfirst($c->status),
                    $c->created_at->format('d M Y'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
