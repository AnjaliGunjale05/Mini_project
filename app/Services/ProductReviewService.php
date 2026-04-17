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
            $exists = ProductReview::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($exists) {
                return 'already_reviewed';
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

    // Get Approved Reviews
    public function getReviews($productId)
    {
        try {
            return ProductReview::with('user')
                ->where('product_id', $productId)
                ->where('is_approved', 1)
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
                ->where('is_approved', 1)
                ->avg('rating') ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    //  Get All Reviews (Admin)
    public function getAllReviews()
    {
        try {
            return ProductReview::with('user', 'product')
                ->latest()
                ->get();
        } catch (Exception $e) {
            return collect();
        }
    }

    //  Approve Review
    public function approve($id)
    {
        try {
            $review = ProductReview::findOrFail($id);
            $review->is_approved = 1;
            $review->save();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    //  Delete Review
    public function delete($id)
    {
        try {
            ProductReview::findOrFail($id)->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}