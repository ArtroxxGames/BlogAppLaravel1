<?php

namespace Tests\Feature\Blog;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_lists_only_published_articles_in_visible_categories(): void
    {
        $published = Article::factory()->create(['title' => 'Artículo publicado']);
        $draft = Article::factory()->draft()->create(['title' => 'Artículo borrador']);
        $scheduled = Article::factory()->scheduled()->create(['title' => 'Artículo programado']);
        $hidden = Article::factory()->for(Category::factory()->hidden())->create(['title' => 'Artículo oculto']);

        $this->get('/')
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee($draft->title)
            ->assertDontSee($scheduled->title)
            ->assertDontSee($hidden->title);
    }

    public function test_home_can_search_articles(): void
    {
        Article::factory()->create(['title' => 'Aprende Laravel desde cero']);
        Article::factory()->create(['title' => 'Consejos de productividad']);

        $this->get('/?q=laravel')
            ->assertOk()
            ->assertSee('Aprende Laravel desde cero')
            ->assertDontSee('Consejos de productividad');
    }

    public function test_featured_categories_appear_in_the_navigation(): void
    {
        Category::factory()->featured()->create(['name' => 'Categoría destacada']);
        Category::factory()->create(['name' => 'Categoría normal']);

        $this->get('/')
            ->assertSee('Categoría destacada')
            ->assertDontSee('Categoría normal');
    }
}
