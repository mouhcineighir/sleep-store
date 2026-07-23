<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ProductResource;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = $request->user()->wishlist()->with('product.images')->get();
        return ProductResource::collection($wishlist->pluck('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $request->user()->wishlist()->firstOrCreate($validated);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function destroy(Request $request, $productId)
    {
        $request->user()->wishlist()->where('product_id', $productId)->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
