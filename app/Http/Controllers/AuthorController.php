<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // List all authors
    public function index()
    {
        return response()->json(Author::all());
    }

    // Show a specific author
    public function show($id)
    {
        $author = Author::find($id);
        if ($author) {
            return response()->json($author);
        }
        return response()->json(['message' => 'Author not found'], 404);
    }

    // Create a new author
    public function store(Request $request)
    {
        // Validation for both 'name' and 'email'
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:authors,email|max:255',  // Email must be valid and unique
        ]);

        // Create the new author with name and email
        $author = Author::create([
            'name' => $request->name,
            'email' => $request->email,  // Save email
        ]);

        return response()->json($author, 201);
    }

    // Update an existing author
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

        // Update the author with provided name and email (if present)
        $author->update($request->only(['name', 'email']));

        return response()->json($author);
    }

    // Delete an author
    public function destroy($id)
    {
        $author = Author::find($id);
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();
        return response()->json(['message' => 'Author deleted']);
    }
}
