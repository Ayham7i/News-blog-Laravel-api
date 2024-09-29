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

    public function index()
    {
        return response()->json($this->commentProxy->getAllComments());
    }

    public function show($id)
    {
        $comment = $this->commentProxy->getCommentById($id);
        if ($comment) {
            return response()->json($comment);
        }
        return response()->json(['message' => 'Comment not found'], 404);
    }

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

        $this->commentProxy->clearCache();

        return response()->json($comment, 201);
    }

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

        $this->commentProxy->clearCache($id);

        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        $this->commentProxy->clearCache($id);

        return response()->json(['message' => 'Comment deleted']);
    }
}
