<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryService
{
    public function getAllCategories(Request $request)
    {
        try {
            $search = $request->search ?? "";
            if ($search != "") {
                // Where clause for Conditioning Entries
                return Category::where('name', 'Like', "%$search%")->latest()->paginate(10);
            } else {
                return Category::latest()->paginate(10);
            }
        } catch (Exception $e) {
            \Log::error($e->getMessage());
    throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $data['slug'] = Str::slug($data['name']);
            return Category::create($data);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
    throw $e;
        }
    }

    public function update(Category $category, array $data)
    {
        try {
            $data['slug'] = Str::slug($data['name']);
            $category->update($data);

            return $category;
        } catch (Exception $e) {
            \Log::error($e->getMessage());
    throw $e;
        }
    }

    public function destroy(Category $category)
    {
        try {
            return $category->delete();
        } catch (Exception $e) {
           \Log::error($e->getMessage());
    throw $e;
        }
    }
}
