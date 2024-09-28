<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\CommentCacheProxy;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentProxy;

    public function __construct(CommentCacheProxy $commentProxy)
    {
        $this->commentProxy = $commentProxy;
    }

    // List all comments (using the cache proxy)
    public function index()
    {
        // Fetch comments using the cache proxy
        return response()->json($this->commentProxy->getAllComments());
    }

    // Show a specific comment (using the cache proxy)
    public function show($id)
    {
        $comment = $this->commentProxy->getCommentById($id);
        if ($comment) {
            return response()->json($comment);
        }
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Create a new comment and clear the cache
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'article_id' => 'required|exists:articles,id',
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'article_id' => $request->article_id,
        ]);

        // Clear cache after creating a new comment
        $this->commentProxy->clearCache();

        return response()->json($comment, 201);
    }

    // Update an existing comment and clear the cache for this comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $request->validate([
            'content' => 'sometimes|string',
        ]);

        $comment->update($request->only(['content']));

        // Clear cache for this comment and the comment list
        $this->commentProxy->clearCache($id);

        return response()->json($comment);
    }

    // Delete a comment and clear the cache
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        // Clear cache for this comment and the comment list
        $this->commentProxy->clearCache($id);

        return response()->json(['message' => 'Comment deleted']);
    }
}
