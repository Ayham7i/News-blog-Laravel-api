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
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'This is the content of the test article',
            'author_id' => $author->id,
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    }

    /** @test */
    public function it_can_update_an_article()
    {
        $article = Article::factory()->create();

        $article->update(['title' => 'Updated Article']);

        $this->assertDatabaseHas('articles', ['title' => 'Updated Article']);
    }

    /** @test */
    public function it_can_delete_an_article()
    {
        $article = Article::factory()->create();

        $article->delete();

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
}
