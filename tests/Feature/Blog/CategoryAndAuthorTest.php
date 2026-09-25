<?php

namespace Tests\Feature\Blog;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAndAuthorTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_index_lists_visible_categories(): void
    {
        Category::factory()->create(['name' => 'Visible']);
        Category::factory()->hidden()->create(['name' => 'Oculta']);

        $this->get(route('categories.index'))
            ->assertOk()
            ->assertSee('Visible')
            ->assertDontSee('Oculta');
    }

    public function test_category_page_lists_its_published_articles(): void
    {
        $category = Category::factory()->create();
        $inCategory = Article::factory()->for($category)->create();
        $other = Article::factory()->create();

        $this->get(route('categories.show', $category))
            ->assertOk()
            ->assertSee($inCategory->title)
            ->assertDontSee($other->title);
    }

    public function test_hidden_category_returns_404(): void
    {
        $category = Category::factory()->hidden()->create();

        $this->get(route('categories.show', $category))->assertNotFound();
    }

    public function test_author_page_shows_profile_and_published_articles(): void
    {
        $author = User::factory()->create(['bio' => 'Escribo sobre backend.']);
        $published = Article::factory()->for($author, 'author')->create();
        $draft = Article::factory()->draft()->for($author, 'author')->create();

        $this->get(route('authors.show', $author))
            ->assertOk()
            ->assertSee('Escribo sobre backend.')
            ->assertSee($published->title)
            ->assertDontSee($draft->title);
    }
}
