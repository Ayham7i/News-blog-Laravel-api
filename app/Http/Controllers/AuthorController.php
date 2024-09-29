<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\AuthorCacheProxy;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected $authorProxy;

    public function __construct(AuthorCacheProxy $authorProxy)
    {
        $this->authorProxy = $authorProxy;
    }

    public function index()
    {

        return response()->json($this->authorProxy->getAllAuthors());

    }

    public function show($id)
    {
        $author = $this->authorProxy->getAuthorById($id);
        if ($author) {
            return response()->json($author);
        }
        return response()->json(['message' => 'Author not found'], 404);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors,email|max:255',  // Email must be valid and unique
        ]);

        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $this->authorProxy->clearCache();

        return response()->json($author, 201);
    }

    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:authors,email,' . $id . '|max:255',  // Ensure unique email except for this author
        ]);

        $author->update($request->only(['name', 'email']));

        $this->authorProxy->clearCache($id);

        return response()->json($author);
    }

    public function destroy($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();

        $this->authorProxy->clearCache($id);

        return response()->json(['message' => 'Author deleted']);
    }
}
