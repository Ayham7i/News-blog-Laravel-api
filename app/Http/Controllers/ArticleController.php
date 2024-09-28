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

    // List all articles (using the cache proxy)
    public function index()
    {
        // Fetch articles using the cache proxy
        return response()->json($this->articleProxy->getAllArticles());
    }

    // Show a specific article (using the cache proxy)
    public function show($id)
    {
        $article = $this->articleProxy->getArticleById($id);
        if ($article) {
            return response()->json($article);
        }
        return response()->json(['message' => 'Article not found'], 404);
    }

    // Create a new article and clear the cache
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

        // Clear cache after creating a new article
        $this->articleProxy->clearCache();

        return response()->json($article, 201);
    }

    // Update an existing article and clear the cache for this article
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

        // Clear cache for this article and the article list
        $this->articleProxy->clearCache($id);

        return response()->json($article);
    }

    // Delete an article and clear the cache
    public function destroy($id)
    {
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        // Clear cache for this article and the article list
        $this->articleProxy->clearCache($id);

        return response()->json(['message' => 'Article deleted']);
    }
}
