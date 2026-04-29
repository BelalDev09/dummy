<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Cart\CartItemResource;
use App\Http\Resources\API\V1\Cart\CartResource;
use App\Http\Resources\API\V1\Cart\OrderResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartApiController extends Controller
{
    use apiresponse;

    //CREATE OR GET CART
    private function getCart(Request $request, bool $create = true)
    {
        $userId = auth()->id();
        $sessionId = $request->header('X-CART-SESSION')
            ?? $request->cookie('cart_session')
            ?? $request->query('session_id');

        if ($create) {
            if ($userId) {
                $cart = Cart::where('status', 'active')
                    ->where('user_id', $userId)
                    ->first();

                if (!$cart && $sessionId) {
                    $cart = Cart::where('status', 'active')
                        ->where('session_id', $sessionId)
                        ->first();

                    if ($cart) {
                        $cart->update(['user_id' => $userId, 'session_id' => null]);
                        return $cart;
                    }
                }

                return Cart::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'session_id' => null,
                        'status' => 'active'
                    ],
                    [
                        'status' => 'active'
                    ]
                );
            }

            if (!$sessionId) {
                $sessionId = uniqid();
                cookie()->queue('cart_session', $sessionId, 60 * 24 * 30);
            }

            return Cart::firstOrCreate(
                [
                    'user_id' => null,
                    'session_id' => $sessionId,
                    'status' => 'active'
                ],
                [
                    'status' => 'active'
                ]
            );
        }

        $query = Cart::where('status', 'active');

        if ($userId) {
            return $query->where('user_id', $userId)->first();
        }

        if (!$sessionId) {
            return null;
        }

        return $query->where('session_id', $sessionId)->first();
    }

    //ADD TO CART
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);

        $cart = $this->getCart($request);
        $product = Product::findOrFail($request->product_id);

        $price = $product->price;

        if ($request->variant_id) {
            $variant = ProductVariant::find($request->variant_id);
            $price = $variant?->price ?? $product->price;
        }

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        $payload = [
            'item' => new CartItemResource($item),
            'cart' => new CartResource($cart),
            'session_id' => $cart->session_id,
        ];

        $response = $this->success('Added to cart', $payload, 200);

        if ($cart->session_id) {
            return $response->cookie('cart_session', $cart->session_id, 60 * 24 * 30);
        }

        return $response;
    }

    //SHOW CART
    public function index(Request $request)
    {
        $cart = $this->getCart($request, false);

        if (!$cart) {
            return $this->success('Cart fetched successfully', [
                'cart' => null,
                'items' => [],
                'total' => 0,
            ]);
        }

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        return $this->success('Cart fetched successfully', [
            'cart' => new CartResource($cart),
            'items' => CartItemResource::collection($items),
            'total' => $items->sum(fn($i) => $i->price * $i->quantity)
        ]);
    }

    //UPDATE ITEM
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::with('product')->findOrFail($request->item_id);

        $item->update([
            'quantity' => $request->quantity
        ]);

        $cart = Cart::find($item->cart_id);

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        return $this->success('Cart updated', [
            'cart' => new CartResource($cart),
            'items' => CartItemResource::collection($items),
            'total' => $items->sum(fn($i) => $i->price * $i->quantity)
        ]);
    }
    //REMOVE ITEM
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        CartItem::findOrFail($request->item_id)->delete();

        return $this->success('Item removed');
    }

    //CHECKOUT
    public function checkout(Request $request)
    {
        $cart = $this->getCart($request, false);

        if (!$cart) {
            return $this->error('Cart not found', 404);
        }

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        if ($items->isEmpty()) {
            return $this->error('Cart is empty', 400);
        }

        $order = null;

        DB::transaction(function () use ($cart, $items, $request, &$order) {

            $subTotal = $items->sum(fn($item) => $item->price * $item->quantity);
            $userId = auth()->id();
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'sub_total' => $subTotal,
                'discount_amount' => 0,
                'total_amount' => $subTotal,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'shipping_address' => json_encode($request->shipping_address ?? []),
                'billing_address' => json_encode($request->billing_address ?? []),
                'notes' => $request->notes ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name ?? 'Product',
                    'product_sku' => $item->product->sku ?? '',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            $cart->update(['status' => 'ordered']);

            CartItem::where('cart_id', $cart->id)->delete();
        });

        return $this->success('Order placed successfully', new OrderResource($order));
    }
}
