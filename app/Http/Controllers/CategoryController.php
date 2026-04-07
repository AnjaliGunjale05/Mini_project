<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Models\Category;

class CategoryController extends Controller
{
    protected $categoryService;

     public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;

    }

    public function index(Request $request){
        $categories = $this->categoryService->getAllCategories($request);
        return view('admin.category.index', compact('categories'));
    }

    public function create(){
        
        return view('admin.category.create');
    }
    
    public function store(Request $request){
        $request->validate([
            'name'=> 'required|unique:categories',
        ]);
        $this->categoryService->create($request->all());

        return redirect()->route('categories.index')->with('success', 'Category created');
    }

    public function edit(Category $category){
        return view('admin.category.edit',compact('category'));
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
        ]);

        $this->categoryService->update($category, $request->all());

        return redirect()->route('categories.index')->with('success', 'Updated');

    }

    public function destroy(Category $category){
        $this->categoryService->destroy($category);

        return back()->with('success', 'Deleted');

    }


}
