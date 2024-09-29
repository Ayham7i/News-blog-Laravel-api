<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class CategoryCacheProxy
{
    protected $cacheTTL = 60;

    public function getAllCategories()
    {
        return Cache::remember('categories', $this->cacheTTL, function () {
            return Category::all();
        });
    }

    public function getCategoryById($id)
    {
        return Cache::remember("category_{$id}", $this->cacheTTL, function () use ($id) {
            return Category::find($id);
        });
    }

    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("category_{$id}");
        }
        Cache::forget('categories'); 
    }
}
