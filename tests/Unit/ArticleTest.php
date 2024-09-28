<?php
namespace Tests\Unit;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_article()
    {
        // Create an author and a category
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        // Now create the article with valid author_id and category_id
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'This is the content of the test article',
            'author_id' => $author->id,
            'category_id' => $category->id,
        ]);

        // Assert the article was created in the database
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
