<?php

namespace Tests\Feature\Blog;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_article_is_public(): void
    {
        $article = Article::factory()->create();

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee($article->title)
            ->assertSee('Inicia sesión');
    }

    public function test_drafts_are_hidden_from_guests_and_other_users(): void
    {
        $article = Article::factory()->draft()->create();

        $this->get(route('articles.show', $article))->assertForbidden();
        $this->actingAs(User::factory()->create())
            ->get(route('articles.show', $article))
            ->assertForbidden();
    }

    public function test_drafts_are_visible_to_their_author_and_admins(): void
    {
        $article = Article::factory()->draft()->create();

        $this->actingAs($article->author)
            ->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Este artículo es un borrador.');

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('articles.show', $article))
            ->assertOk();
    }

    public function test_body_is_rendered_as_markdown_without_raw_html(): void
    {
        $article = Article::factory()->create([
            'body' => "## Subtítulo\n\nTexto en **negrita**.\n\n<script>alert('xss')</script>\n\n[enlace](javascript:alert(1))",
        ]);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('<h2>Subtítulo</h2>', false)
            ->assertSee('<strong>negrita</strong>', false)
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('javascript:alert', false);
    }

    public function test_articles_in_hidden_categories_are_not_public(): void
    {
        $article = Article::factory()->for(Category::factory()->hidden())->create();

        $this->get(route('articles.show', $article))->assertForbidden();
        $this->actingAs($article->author)->get(route('articles.show', $article))->assertOk();
    }

    public function test_unknown_article_returns_404(): void
    {
        $this->get('/articulos/no-existe')->assertNotFound();
    }
}
