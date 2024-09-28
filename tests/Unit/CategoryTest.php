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
        // Create a category
        $category = Category::create([
            'name' => 'Technology',
        ]);

        // Assert that the category was created successfully
        $this->assertDatabaseHas('categories', [
            'name' => 'Technology',
        ]);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        // Create a category
        $category = Category::factory()->create();

        // Update the category
        $category->update(['name' => 'Updated Category']);

        // Assert that the category was updated successfully
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        // Create a category
        $category = Category::factory()->create();

        // Delete the category
        $category->delete();

        // Assert that the category was deleted
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
