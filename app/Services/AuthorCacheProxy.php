<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Author;

class AuthorCacheProxy
{
    protected $cacheTTL = 60; // Cache Time To Live (TTL) in minutes

    // Retrieve all authors, either from cache or from the database
    public function getAllAuthors()
    {
        return Cache::remember('authors', $this->cacheTTL, function () {
            return Author::all();  // Fetch authors from the database if not in cache
        });
    }

    // Retrieve a single author by ID, either from cache or from the database
    public function getAuthorById($id)
    {
        return Cache::remember("author_{$id}", $this->cacheTTL, function () use ($id) {
            return Author::find($id);  // Fetch from the database if not in cache
        });
    }

    // Invalidate cache when an author is created, updated, or deleted
    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("author_{$id}");  // Clear specific author cache
        }
        Cache::forget('authors');  // Clear all authors cache
    }
}
