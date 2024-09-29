<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ArticleCacheProxy;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleProxy;

    public function __construct(ArticleCacheProxy $articleProxy)
    {
        $this->articleProxy = $articleProxy;
    }

    public function index()
    {
        return response()->json($this->articleProxy->getAllArticles());
    }

    public function show($id)
    {
        $article = $this->articleProxy->getArticleById($id);
        if ($article) {
            return response()->json($article);
        }
        return response()->json(['message' => 'Article not found'], 404);
    }

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

        $this->articleProxy->clearCache();

        return response()->json($article, 201);
    }

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

        $this->articleProxy->clearCache($id);

        return response()->json($article);
    }

    public function destroy($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        $this->articleProxy->clearCache($id);

        return response()->json(['message' => 'Article deleted']);
    }
}
