<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Article;

class ArticleCacheProxy
{
    protected $cacheTTL = 60;

    public function getAllArticles()
    {
        return Cache::remember('articles', $this->cacheTTL, function () {
            return Article::all();
        });
    }

    public function getArticleById($id)
    {
        return Cache::remember("article_{$id}", $this->cacheTTL, function () use ($id) {
            return Article::find($id);
        });
    }

    // Invalidate cache when an article is created, updated, or deleted
    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("article_{$id}");  
        }
        Cache::forget('articles');
    }
}
