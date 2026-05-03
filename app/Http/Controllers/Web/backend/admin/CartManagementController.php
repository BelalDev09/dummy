<?php

namespace App\Http\Controllers\Web\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Cart::with(['user', 'items.product', 'items.variant'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                )->orWhereHas(
                    'items.product',
                    fn($p) =>
                    $p->where('name', 'like', "%{$search}%")
                );
            });
        }

        $carts = $query->paginate(15)->withQueryString();

        $stats = [
            'total_active'  => Cart::where('status', 'active')->count(),
            'total_value'   => CartItem::whereHas('cart', fn($query) => $query->where('status', 'active'))
                ->sum(DB::raw('price * quantity')),
            'unique_users'  => Cart::where('status', 'active')
                ->distinct('user_id')->count('user_id'),
            'ordered_today' => Cart::where('status', 'ordered')
                ->whereDate('updated_at', today())->count(),
        ];

        return view('backend.layout.admin.cart.index', compact('carts', 'stats'));
    }

    public function show(Cart $cart)
    {
        $cart->load([
            'user',
            'items.product.brand',
            'items.product.category',
            'items.product.subCategory',
            'items.variant'
        ]);

        return view('backend.layout.admin.cart.show', compact('cart'));
    }

    public function updateStatus(Request $request, Cart $cart)
    {
        $request->validate([
            'status' => 'required|in:active,ordered,removed',
        ]);

        $cart->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.carts.index')->with('success', 'Cart status updated successfully.');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('admin.carts.index')
            ->with('success', 'Cart item deleted.');
    }

    public function export(Request $request)
    {
        $query = Cart::with(['user', 'items.product', 'items.variant'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $carts = $query->get();

        $filename = 'carts_' . now()->format('Y_m_d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($carts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Email', 'Products', 'Quantity', 'Total', 'Status', 'Date']);

            foreach ($carts as $c) {
                $productNames = $c->items->pluck('product.name')->filter()->unique()->values()->all();
                $products = implode(', ', $productNames) ?: 'No products';
                $quantity = $c->items->sum('quantity');
                $total = $c->items->sum(fn($item) => $item->price * $item->quantity);

                fputcsv($handle, [
                    $c->id,
                    $c->user?->name ?? 'Guest',
                    $c->user?->email ?? '-',
                    $products,
                    $quantity,
                    number_format($total, 2),
                    $c->status,
                    $c->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
