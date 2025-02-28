<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_category()
    {
        $category = Category::create([
            'name' => 'Technology',
        ]);


        $this->assertDatabaseHas('categories', [
            'name' => 'Technology',
        ]);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();

        $category->update(['name' => 'Updated Category']);

        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {

        $category = Category::factory()->create();


        $category->delete();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
