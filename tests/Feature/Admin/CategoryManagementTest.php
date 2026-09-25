<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
    }

    public function test_non_admins_cannot_access_the_admin_area(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.categories.store'), ['name' => 'X'])->assertForbidden();
        $this->actingAs($user)->delete(route('admin.categories.destroy', $category))->assertForbidden();
        $this->actingAs($user)->get(route('admin.comments.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_admin_pages_render(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)->get(route('admin.categories.index'))->assertOk()->assertSee($category->name);
        $this->actingAs($this->admin)->get(route('admin.categories.create'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.categories.edit', $category))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.comments.index'))->assertOk();
        $this->actingAs($this->admin)->get(route('admin.users.index'))->assertOk();
    }

    public function test_admin_can_create_a_category(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Diseño Web',
                'description' => 'Todo sobre diseño.',
                'image' => UploadedFile::fake()->image('cat.png'),
                'is_visible' => '1',
                'is_featured' => '1',
            ])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::sole();
        $this->assertSame('diseno-web', $category->slug);
        $this->assertTrue($category->is_featured);
        Storage::disk('public')->assertExists($category->image_path);
    }

    public function test_category_names_must_be_unique(): void
    {
        $existing = Category::factory()->create(['name' => 'Laravel']);

        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), ['name' => 'Laravel'])
            ->assertSessionHasErrors('name');

        // Al editar, su propio nombre no cuenta como duplicado.
        $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $existing), ['name' => 'Laravel', 'is_visible' => '0'])
            ->assertSessionHasNoErrors();

        $this->assertFalse($existing->refresh()->is_visible);
    }

    public function test_category_with_articles_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Article::factory()->for($category)->create();

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');

        $this->assertModelExists($category);
    }

    public function test_empty_category_can_be_deleted_with_its_image(): void
    {
        $image = UploadedFile::fake()->image('cat.png')->store('categories', 'public');
        $category = Category::factory()->create(['image_path' => $image]);

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertModelMissing($category);
        Storage::disk('public')->assertMissing($image);
    }
}
