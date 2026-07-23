<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->user()->cart()->with('items.product.images')->first();

        return CartItemResource::collection($cart->items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $request->user()->cart;

        // If this product is already in the cart, increase quantity instead of duplicating
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();

        if ($item) {
            $item->increment('quantity', $validated['quantity']);
        } else {
            $item = $cart->items()->create($validated);
        }

        return new CartItemResource($item->load('product.images'));
    }

    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = $request->user()->cart->items()->findOrFail($itemId);
        $item->update($validated);

        return new CartItemResource($item->load('product.images'));
    }

    public function destroy(Request $request, $itemId)
    {
        $item = $request->user()->cart->items()->findOrFail($itemId);
        $item->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}