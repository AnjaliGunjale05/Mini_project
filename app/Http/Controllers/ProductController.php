<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    // Inject Service
    // Constructor _construct Special method

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
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
            'images.*' => 'image|mimes:jpg,png,jpeg| max:2048'
        ]);

        // Call Service

       $product= $this->productService->createProduct($request);


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
        ]);

        // call Service
        $this->productService->updateProduct($request, $product);


        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        // call service
        $this->productService->deleteProduct($product);
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    public function show(Product $product)
    {
        $product->load('category','images');

        // Related Product

        $relatedProducts=Product::where('category_id',$product->category_id)
        ->where('id', '!=' , $product->id)
        ->inRandomOrder()
        ->take(4)
        ->get();
        return view('show', compact('product','relatedProducts'));
    }
}
