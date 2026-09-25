<?php

namespace Tests\Feature\Dashboard;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    /**
     * @return array<string, mixed>
     */
    private function validData(array $overrides = []): array
    {
        return [
            'title' => 'Mi primer artículo',
            'excerpt' => 'Un resumen suficientemente largo.',
            'body' => "## Hola\n\nEste es el contenido del artículo.",
            'category_id' => Category::factory()->create()->id,
            'status' => 'published',
            ...$overrides,
        ];
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('dashboard.articles.create'))->assertRedirect(route('login'));
    }

    public function test_dashboard_pages_render(): void
    {
        $user = User::factory()->create();
        Article::factory()->for($user, 'author')->create(['title' => 'Mi artículo']);

        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.articles.index'))->assertOk()->assertSee('Mi artículo');
        $this->actingAs($user)->get(route('dashboard.articles.create'))->assertOk();
    }

    public function test_index_only_lists_the_users_own_articles(): void
    {
        $user = User::factory()->create();
        Article::factory()->create(['title' => 'Artículo ajeno']);

        $this->actingAs($user)
            ->get(route('dashboard.articles.index'))
            ->assertDontSee('Artículo ajeno');
    }

    public function test_user_can_create_an_article_with_a_cover(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('dashboard.articles.store'), $this->validData([
                'cover' => UploadedFile::fake()->image('portada.jpg'),
            ]))
            ->assertRedirect(route('dashboard.articles.index'))
            ->assertSessionHasNoErrors();

        $article = Article::sole();

        $this->assertSame($user->id, $article->user_id);
        $this->assertSame('mi-primer-articulo', $article->slug);
        $this->assertTrue($article->isPublished());
        Storage::disk('public')->assertExists($article->cover_path);
    }

    public function test_slugs_are_unique(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('dashboard.articles.store'), $this->validData());
        $this->actingAs($user)->post(route('dashboard.articles.store'), $this->validData());

        $this->assertSame(['mi-primer-articulo', 'mi-primer-articulo-2'], Article::orderBy('id')->pluck('slug')->all());
    }

    public function test_article_can_be_saved_as_draft_or_scheduled(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('dashboard.articles.store'), $this->validData(['status' => 'draft', 'title' => 'Borrador']));
        $this->actingAs($user)->post(route('dashboard.articles.store'), $this->validData([
            'title' => 'Programado',
            'published_at' => now()->addDay()->format('Y-m-d H:i'),
        ]));

        $this->assertNull(Article::firstWhere('title', 'Borrador')->published_at);
        $this->assertTrue(Article::firstWhere('title', 'Programado')->isScheduled());
    }

    public function test_article_data_is_validated(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('dashboard.articles.store'), [
                'title' => 'abc',
                'category_id' => 999,
                'status' => 'otro',
                'cover' => UploadedFile::fake()->create('virus.exe', 10),
            ])
            ->assertSessionHasErrors(['title', 'excerpt', 'body', 'category_id', 'status', 'cover']);

        $this->assertDatabaseCount('articles', 0);
    }

    public function test_hidden_categories_cannot_be_used(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('dashboard.articles.store'), $this->validData([
                'category_id' => Category::factory()->hidden()->create()->id,
            ]))
            ->assertSessionHasErrors('category_id');
    }

    public function test_author_can_update_their_article_without_changing_its_slug(): void
    {
        $article = Article::factory()->create(['title' => 'Título original']);
        $slug = $article->slug;

        $this->actingAs($article->author)
            ->put(route('dashboard.articles.update', $article), $this->validData(['title' => 'Título nuevo']))
            ->assertRedirect(route('dashboard.articles.index'));

        $article->refresh();
        $this->assertSame('Título nuevo', $article->title);
        $this->assertSame($slug, $article->slug);
    }

    public function test_updating_the_cover_replaces_the_old_file(): void
    {
        $oldCover = UploadedFile::fake()->image('vieja.jpg')->store('articles', 'public');
        $article = Article::factory()->create(['cover_path' => $oldCover]);

        $this->actingAs($article->author)->put(route('dashboard.articles.update', $article), $this->validData([
            'cover' => UploadedFile::fake()->image('nueva.jpg'),
        ]));

        Storage::disk('public')->assertMissing($oldCover);
        Storage::disk('public')->assertExists($article->refresh()->cover_path);
    }

    public function test_cover_can_be_removed(): void
    {
        $cover = UploadedFile::fake()->image('portada.jpg')->store('articles', 'public');
        $article = Article::factory()->create(['cover_path' => $cover]);

        $this->actingAs($article->author)->put(route('dashboard.articles.update', $article), $this->validData([
            'remove_cover' => '1',
        ]));

        $this->assertNull($article->refresh()->cover_path);
        Storage::disk('public')->assertMissing($cover);
    }

    public function test_users_cannot_edit_or_delete_other_users_articles(): void
    {
        $article = Article::factory()->create();
        $intruder = User::factory()->create();

        $this->actingAs($intruder)->get(route('dashboard.articles.edit', $article))->assertForbidden();
        $this->actingAs($intruder)->put(route('dashboard.articles.update', $article), $this->validData())->assertForbidden();
        $this->actingAs($intruder)->delete(route('dashboard.articles.destroy', $article))->assertForbidden();

        $this->assertModelExists($article);
    }

    public function test_admins_can_edit_any_article(): void
    {
        $article = Article::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('dashboard.articles.update', $article), $this->validData(['title' => 'Editado por admin']))
            ->assertRedirect();

        $this->assertSame('Editado por admin', $article->refresh()->title);
        $this->assertNotNull($article->user_id);
    }

    public function test_author_can_delete_their_article_and_its_cover(): void
    {
        $cover = UploadedFile::fake()->image('portada.jpg')->store('articles', 'public');
        $article = Article::factory()->create(['cover_path' => $cover]);

        $this->actingAs($article->author)
            ->delete(route('dashboard.articles.destroy', $article))
            ->assertRedirect(route('dashboard.articles.index'));

        $this->assertModelMissing($article);
        Storage::disk('public')->assertMissing($cover);
    }
}
