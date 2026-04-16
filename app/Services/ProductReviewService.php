<?php 
namespace App\Services;

use App\Models\ProductReview;
use Exception;

class ProductReviewService
{
    // Store Review
    public function storeReview($request)
    {
        try {
        // Prevent duplicate review (1 user → 1 product)
        $exists = ProductReview::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            return false;
        }

        return ProductReview::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'is_approved' => 0
        ]);

    } catch (Exception $e) {
        return false;
    }
    }

    // Get Reviews
    public function getReviews($productId)
    {
        try {
            return ProductReview::where('product_id', $productId)
                ->latest()
                ->take(5)
                ->get();
        } catch (Exception $e) {
            return collect();
        }
    }

    // Average Rating
    public function getAverageRating($productId)
    {
        try {
            return ProductReview::where('product_id', $productId)
                // ->where('is_approved', 1)
                ->avg('rating');
        } catch (Exception $e) {
            return 0;
        }
    }
}