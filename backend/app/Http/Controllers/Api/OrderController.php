<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Mail\OrderConfirmationMail;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->paginate(10);
        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order)
    {
        // Make sure users can only see their own orders
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'This is not your order.');
        }

        return new OrderResource($order->load('items.product', 'address'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,stripe',
            'coupon_code' => 'nullable|string',
        ]);

        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty'], 422);
        }

        $order = DB::transaction(function () use ($user, $cart, $validated) {
            // 1. Calculate subtotal from current cart
            $subtotal = $cart->items->sum(fn ($item) => $item->quantity * $item->product->price);

            // 2. Apply coupon if provided
            $discount = 0;
            $couponId = null;
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])->first();
                if ($coupon && (!$coupon->expires_at || !$coupon->expires_at->isPast())) {
                    $discount = $coupon->type === 'percentage'
                        ? $subtotal * ($coupon->discount / 100)
                        : $coupon->discount;
                    $couponId = $coupon->id;
                }
            }

            // 3. Simple flat rules for shipping and tax (adjust later if needed)
            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $tax = round($subtotal * 0.05, 2);
            $total = $subtotal + $shipping + $tax - $discount;

            // 4. Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $validated['address_id'],
                'coupon_id' => $couponId,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
            ]);

            // 5. Snapshot each cart item into order_items, and reduce stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // 6. Empty the cart
            $cart->items()->delete();

            return $order;
        });

        Mail::to($user->email)->send(new OrderConfirmationMail($order->load('items.product')));

        return new OrderResource($order->load('items.product'));
    }
}