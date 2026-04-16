<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\RecentProductService;
use App\Services\ProductReviewService;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImages;
use App\Models\ProductReview;

use Exception;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected $productService;
    protected $recentProductService;
    protected $reviewService;
    // Inject Service
    // Constructor _construct Special method

    public function __construct(ProductService $productService, RecentProductService $recentProductService, ProductReviewService $reviewService)
    {
        $this->productService = $productService;
        $this->recentProductService = $recentProductService;
        $this->reviewService = $reviewService;
    }
    //  Display List of product 
    public function index(Request $request)
    {

        $products = $this->productService->getAllProducts($request);
        $categories = Category::all();

        if (auth()->check() && auth()->user()->role === 'admin') {
            return view('admin.products.index', compact('products', 'categories'));
        }

        return view('shop', compact('products', 'categories'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'images.*' => 'image|mimes:jpg,png,jpeg| max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Call Service

        $product = $this->productService->createProduct($request);

        //    attach categories
        $product->categories()->attach($request->categories);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Show edit form

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'images.*' => 'image|mimes:jpg,png,jpeg| max:2048'
        ]);

        // call Service
        $this->productService->updateProduct($request, $product);
        // sync categories
        $product->categories()->sync($request->categories);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        $product->categories()->detach();
        // call service
        $this->productService->deleteProduct($product);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function show(Product $product)
    {
        try {

            $product->load('categories', 'images');

            // Store Review and rating 
            $reviews = $this->reviewService->getReviews($product->id);
            $avgRating = $this->reviewService->getAverageRating($product->id);

            // Store Recently Viewed Products
            $this->recentProductService->store($product->id);

            // get category ids
            $categoryIds = $product->categories->pluck('id');

            // Related Product

            $relatedProducts = Product::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(4)
                ->get();

            //  Get Recent Products for sidebar or section
            $recentProducts = $this->recentProductService->getRecentProducts();

            return view(
                'show',
                compact(
                    'product',
                    'relatedProducts',
                    'recentProducts',
                    'reviews',
                    'avgRating'
                )
            );
        } catch (Exception $e) {
            Log::error('Product Show Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load product details at this time.');
        }
    }

    public function storeReview(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000'
            ]);

            // Must be logged in
            if (!Auth::check()) {
                return redirect()->back()->with('error', 'Please login to submit review.');
            }

            $result = $this->reviewService->storeReview($request);

            if ($result === 'already_reviewed') {
                return redirect()->back()->with('error', 'You already reviewed this product.');
            }

            if ($result) {
                return redirect()->back()->with('success', 'Review submitted successfully! Waiting for approval.');
            }

            return redirect()->back()->with('error', 'Failed to submit review.');
        } catch (Exception $e) {
            Log::error('Review Store Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
}
