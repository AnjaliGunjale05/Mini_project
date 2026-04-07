<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImages;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{
    // Get all Products

    public function getAllProducts(Request $request)
    {
        try {
            $query = Product::query();

            // Search filter
            if (!empty($request->search)) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }

            // Category filter
            if (!empty($request->category)) {
                $query->where('category_id', $request->category);
            }

            return $query->latest()->paginate(12);
        } catch (Exception $e) {
            return false;
        }
    }

    // store Products

    public function createProduct($request)
    {
        try {
            $data = $request->all();

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
            $data = $request->all();

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

            foreach($product->images as $img){
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
}
