<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImages;
use App\Models\ProductReview;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{
    // Get all Products

    public function getAllProducts(Request $request)
    {
        try {
            $query = Product::with('categories');

            // Search by product name OR category name

            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhereHas('categories', function ($q2) use ($search) {
                            $q2->where('name', 'LIKE', "%$search%");
                        });
                });
            }

            // Multti-Category filter
            if (!empty($request->category)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('categories.id', $request->category);
                });
            }

            // Price Filter

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Rating Filter

            if ($request->filled('rating'))
                $query->whereHas('reviews', function ($q) use ($request) {
                    $q->where('rating', '>=', $request->rating);
                });

            return $query->latest()->paginate(12);
        } catch (Exception $e) {
            \Log::error($e->getMessage());

            return Product::latest()->paginate(12);
        }
    }

    // store Products

    public function createProduct($request)
    {
        try {
            $data = $request->except('categories');

            if ($request->hasfile('image')) {
                $data['image'] = $request->file('image')->store('uploads/products', 'public');
            }

            // Create Product

            $product = Product::create($data);
            // 
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('uploads/products', 'public');

                    ProductImages::create(
                        [
                            'product_id' => $product->id,
                            'image_path' => $path,
                        ]
                    );
                }
            }
            return $product;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    // Update Product

    public function updateProduct($request, $product)
    {
        try {
            $data = $request->except('categories');

            if ($request->hasfile('image')) {
                // Delete old image

                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('uploads/products', 'public');
            }

            // Update product images
            $product->update($data);
            if ($request->hasFile('images')) {

                //  Delete old gallery images
                foreach ($product->images as $img) {
                    if (Storage::disk('public')->exists($img->image_path)) {
                        Storage::disk('public')->delete($img->image_path);
                    }
                    $img->delete();
                }

                // New Gallery Image

                foreach ($request->file('images') as $img) {
                    $path = $img->store('uploads/products', 'public');

                    ProductImages::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                    ]);
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Delete Product

    public function deleteProduct($product)
    {
        try {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            foreach ($product->images as $img) {
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                $img->delete();
            }
            return $product->delete();
        } catch (Exception $e) {
            return false;
        }
    }

    //  Product Analutics - Increase View Count

    public function increaseView($product)
    {
        try {
            $sessionkey = 'viewed_product_' . $product->id;
            if (!session()->has($sessionkey)) {
                $product->increment('views');
                session()->put($sessionkey, true);
            }
            return true;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return false;
        }
    }

    // Top Selling Products for Analytics

    public function getTopSellingProducts($limit = 10)
    {
        try {
            return Product::orderby('sales_count', 'desc')
                ->take($limit)
                ->get();
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return collect();
        }
    }

    // Most Viewed Products for Analytics

    public function getMostViewedProducts($limit = 10)
    {
        try {
            return Product::orderByDesc('views')
                ->take($limit)
                ->get();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return collect();
        }
    }
}
