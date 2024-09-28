<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_author()
    {
        // Create an author
        $author = Author::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Assert that the author was created successfully
        $this->assertDatabaseHas('authors', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_can_update_an_author()
    {
        // Create an author
        $author = Author::factory()->create();

        // Update the author
        $author->update(['name' => 'Jane Doe']);

        // Assert that the author was updated successfully
        $this->assertDatabaseHas('authors', ['name' => 'Jane Doe']);
    }

    /** @test */
    public function it_can_delete_an_author()
    {
        // Create an author
        $author = Author::factory()->create();

        // Delete the author
        $author->delete();

        // Assert that the author was deleted
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
