<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $request->user()->reviews()->create([
            'product_id' => $productId,
            ...$validated,
        ]);

        return new ReviewResource($review->load('user'));
    }

    public function destroy(Request $request, $reviewId)
    {
        $review = $request->user()->reviews()->findOrFail($reviewId);
        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}