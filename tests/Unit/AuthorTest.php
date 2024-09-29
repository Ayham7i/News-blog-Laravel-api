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
        $author = Author::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('authors', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    /** @test */
    public function it_can_update_an_author()
    {
        $author = Author::factory()->create();

        $author->update(['name' => 'Jane Doe']);

        $this->assertDatabaseHas('authors', ['name' => 'Jane Doe']);
    }

    /** @test */
    public function it_can_delete_an_author()
    {
        $author = Author::factory()->create();

        $author->delete();

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
