<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryCacheProxy;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryProxy;

    public function __construct(CategoryCacheProxy $categoryProxy)
    {
        $this->categoryProxy = $categoryProxy;
    }

    // List all categories (using the cache proxy)
    public function index()
    {
        // Fetch categories using the cache proxy
        return response()->json($this->categoryProxy->getAllCategories());
    }

    // Show a specific category (using the cache proxy)
    public function show($id)
    {
        $category = $this->categoryProxy->getCategoryById($id);
        if ($category) {
            return response()->json($category);
        }
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Create a new category and clear the cache
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        // Clear cache after creating a new category
        $this->categoryProxy->clearCache();

        return response()->json($category, 201);
    }

    // Update an existing category and clear the cache for this category
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $category->update($request->only(['name']));

        // Clear cache for this category and the category list
        $this->categoryProxy->clearCache($id);

        return response()->json($category);
    }

    // Delete a category and clear the cache
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        // Clear cache for this category and the category list
        $this->categoryProxy->clearCache($id);

        return response()->json(['message' => 'Category deleted']);
    }
}
