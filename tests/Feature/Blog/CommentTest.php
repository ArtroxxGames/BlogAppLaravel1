<?php

namespace Tests\Feature\Blog;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_comment(): void
    {
        $article = Article::factory()->create();

        $this->post(route('comments.store', $article), ['rating' => 5, 'body' => 'Genial'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_users_can_comment_and_rate_an_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $this->actingAs($user)
            ->post(route('comments.store', $article), ['rating' => 4, 'body' => 'Muy útil, gracias.'])
            ->assertRedirect(route('articles.show', $article).'#comentarios')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'article_id' => $article->id,
            'rating' => 4,
            'body' => 'Muy útil, gracias.',
        ]);
    }

    public function test_users_can_only_comment_once_per_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        Comment::factory()->for($article)->for($user, 'author')->create();

        $this->actingAs($user)
            ->post(route('comments.store', $article), ['rating' => 5, 'body' => 'Otra vez'])
            ->assertForbidden();

        $this->assertDatabaseCount('comments', 1);
    }

    public function test_drafts_cannot_be_commented(): void
    {
        $article = Article::factory()->draft()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('comments.store', $article), ['rating' => 5, 'body' => 'Hola'])
            ->assertForbidden();
    }

    public function test_comment_is_validated(): void
    {
        $article = Article::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post(route('comments.store', $article), ['rating' => 9, 'body' => ''])
            ->assertSessionHasErrors(['rating', 'body']);
    }

    public function test_comment_can_be_deleted_by_its_author_the_article_author_or_an_admin(): void
    {
        foreach (['comment author', 'article author', 'admin'] as $who) {
            $comment = Comment::factory()->create();

            $user = match ($who) {
                'comment author' => $comment->author,
                'article author' => $comment->article->author,
                'admin' => User::factory()->admin()->create(),
            };

            $this->actingAs($user)
                ->delete(route('comments.destroy', $comment))
                ->assertRedirect();

            $this->assertModelMissing($comment);
        }
    }

    public function test_other_users_cannot_delete_comments(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('comments.destroy', $comment))
            ->assertForbidden();

        $this->assertModelExists($comment);
    }
}
