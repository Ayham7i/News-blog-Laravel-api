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

    // List all authors (using the cache proxy)
    public function index()
    {
        // Fetch authors using the cache proxy
        return response()->json($this->authorProxy->getAllAuthors());
    }

    // Show a specific author (using the cache proxy)
    public function show($id)
    {
        $author = $this->authorProxy->getAuthorById($id);
        if ($author) {
            return response()->json($author);
        }
        return response()->json(['message' => 'Author not found'], 404);
    }

    // Create a new author and clear the cache
    public function store(Request $request)
    {
        // Validation for both 'name' and 'email'
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors,email|max:255',  // Email must be valid and unique
        ]);

        // Create the new author
        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,  // Save email
        ]);

        // Clear cache after creating a new author
        $this->authorProxy->clearCache();

        return response()->json($author, 201);
    }

    // Update an existing author and clear the cache for this author
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        // Validation for both 'name' and 'email'
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:authors,email,' . $id . '|max:255',  // Ensure unique email except for this author
        ]);

        // Update the author
        $author->update($request->only(['name', 'email']));

        // Clear cache for this author and the authors list
        $this->authorProxy->clearCache($id);

        return response()->json($author);
    }

    // Delete an author and clear the cache
    public function destroy($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        // Delete the author
        $author->delete();

        // Clear cache for this author and the authors list
        $this->authorProxy->clearCache($id);

        return response()->json(['message' => 'Author deleted']);
    }
}
