<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // List all articles
    public function index()
    {
        return response()->json(Article::all());
    }

    // Show a specific article
    public function show($id)
    {
        $article = Article::find($id);
        if ($article) {
            return response()->json($article);
        }
        return response()->json(['message' => 'Article not found'], 404);
    }

    // Create a new article
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
        ]);

        return response()->json($article, 201);
    }

    // Update an existing article
    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'author_id' => 'sometimes|exists:authors,id',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $article->update($request->only(['title', 'content', 'author_id', 'category_id']));
        return response()->json($article);
    }

    // Delete an article
    public function destroy($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
