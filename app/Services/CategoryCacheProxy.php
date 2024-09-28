<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class CategoryCacheProxy
{
    protected $cacheTTL = 60; // Cache Time To Live (TTL) in minutes

    // Retrieve all categories, either from cache or from the database
    public function getAllCategories()
    {
        return Cache::remember('categories', $this->cacheTTL, function () {
            return Category::all();  // Fetch categories from the database if not in cache
        });
    }

    // Retrieve a single category by ID, either from cache or from the database
    public function getCategoryById($id)
    {
        return Cache::remember("category_{$id}", $this->cacheTTL, function () use ($id) {
            return Category::find($id);  // Fetch from the database if not in cache
        });
    }

    // Invalidate cache when a category is created, updated, or deleted
    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("category_{$id}");  // Clear specific category cache
        }
        Cache::forget('categories');  // Clear all categories cache
    }
}
