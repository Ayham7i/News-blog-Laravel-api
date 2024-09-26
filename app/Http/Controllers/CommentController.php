<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // List all comments
    public function index()
    {
        return response()->json(Comment::all());
    }

    // Show a specific comment
    public function show($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            return response()->json($comment);
        }
        return response()->json(['message' => 'Comment not found'], 404);
    }

    // Create a new comment
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

        return response()->json($comment, 201);
    }

    // Update an existing comment
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
        return response()->json($comment);
    }

    // Delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
