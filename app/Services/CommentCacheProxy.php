<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Comment;

class CommentCacheProxy
{
    protected $cacheTTL = 60; // Cache Time To Live (TTL) in minutes

    // Retrieve all comments, either from cache or from the database
    public function getAllComments()
    {
        return Cache::remember('comments', $this->cacheTTL, function () {
            return Comment::all();  // Fetch comments from the database if not in cache
        });
    }

    // Retrieve a single comment by ID, either from cache or from the database
    public function getCommentById($id)
    {
        return Cache::remember("comment_{$id}", $this->cacheTTL, function () use ($id) {
            return Comment::find($id);  // Fetch from the database if not in cache
        });
    }

    // Invalidate cache when a comment is created, updated, or deleted
    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("comment_{$id}");  // Clear specific comment cache
        }
        Cache::forget('comments');  // Clear all comments cache
    }
}
