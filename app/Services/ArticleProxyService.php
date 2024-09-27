<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Article;

class ArticleProxyService
{
    protected $cacheTTL = 60; // Cache Time To Live (TTL) in minutes

    // Retrieve all articles, either from cache or from the database
    public function getAllArticles()
    {
        return Cache::remember('articles', $this->cacheTTL, function () {
            return Article::all();  // Fetch articles from database if not in cache
        });
    }

    // Retrieve a single article by ID, either from cache or from the database
    public function getArticleById($id)
    {
        return Cache::remember("article_{$id}", $this->cacheTTL, function () use ($id) {
            return Article::findOrFail($id);  // Fetch from database if not in cache
        });
    }

    // Invalidate the cache when an article is updated or deleted
    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("article_{$id}");  // Clear specific article cache
        }
        Cache::forget('articles');  // Clear all articles cache
    }
}
