<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Author;

class AuthorCacheProxy
{
    protected $cacheTTL = 60;


    public function getAllAuthors()
    {
        return Cache::remember('authors', $this->cacheTTL, function () {
            return Author::all();
        });
    }

    public function getAuthorById($id)
    {
        return Cache::remember("author_{$id}", $this->cacheTTL, function () use ($id) {
            return Author::find($id);
        });
    }

    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("author_{$id}");
        }
        Cache::forget('authors'); 
    }
}
