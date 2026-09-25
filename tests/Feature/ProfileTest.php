<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_public_profile_fields_and_avatar_can_be_updated(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'profession' => 'Desarrolladora',
                'bio' => 'Me encanta PHP.',
                'github_url' => 'https://github.com/ejemplo',
                'avatar' => UploadedFile::fake()->image('yo.jpg'),
            ])
            ->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Desarrolladora', $user->profession);
        $this->assertSame('https://github.com/ejemplo', $user->github_url);
        Storage::disk('public')->assertExists($user->avatar_path);

        $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
            'remove_avatar' => '1',
        ]);

        Storage::disk('public')->assertMissing($user->avatar_path);
        $this->assertNull($user->refresh()->avatar_path);
    }

    public function test_social_links_must_be_urls(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'twitter_url' => 'javascript:alert(1)',
            ])
            ->assertSessionHasErrors('twitter_url');
    }

    public function test_deleting_the_account_removes_articles_and_files(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'avatar_path' => UploadedFile::fake()->image('yo.jpg')->store('avatars', 'public'),
        ]);
        $article = Article::factory()->for($user, 'author')->create([
            'cover_path' => UploadedFile::fake()->image('portada.jpg')->store('articles', 'public'),
        ]);

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertModelMissing($article);
        Storage::disk('public')->assertMissing([$user->avatar_path, $article->cover_path]);
    }
}
