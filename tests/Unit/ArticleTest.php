<?php
namespace Tests\Unit;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_article()
    {
        // Create an article
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'This is the content of the test article',
            'author_id' => 1,
            'category_id' => 1,
        ]);

        // Assert that the article was created successfully
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    }

    /** @test */
    public function it_can_update_an_article()
    {
        // Create an article
        $article = Article::factory()->create();

        // Update the article
        $article->update(['title' => 'Updated Article']);

        // Assert the update
        $this->assertDatabaseHas('articles', ['title' => 'Updated Article']);
    }

    /** @test */
    public function it_can_delete_an_article()
    {
        // Create an article
        $article = Article::factory()->create();

        // Delete the article
        $article->delete();

        // Assert the deletion
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
