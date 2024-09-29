<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Comment;

class CommentCacheProxy
{
    protected $cacheTTL = 60;


    public function getAllComments()
    {
        return Cache::remember('comments', $this->cacheTTL, function () {
            return Comment::all();
        });
    }


    public function getCommentById($id)
    {
        return Cache::remember("comment_{$id}", $this->cacheTTL, function () use ($id) {
            return Comment::find($id);
        });
    }


    public function clearCache($id = null)
    {
        if ($id) {
            Cache::forget("comment_{$id}");
        }
        Cache::forget('comments');  
    }
}
