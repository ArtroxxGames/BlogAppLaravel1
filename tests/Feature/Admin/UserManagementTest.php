<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_promote_and_demote_users(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->patch(route('admin.users.toggle-admin', $user))->assertRedirect();
        $this->assertTrue($user->refresh()->is_admin);

        $this->actingAs($admin)->patch(route('admin.users.toggle-admin', $user))->assertRedirect();
        $this->assertFalse($user->refresh()->is_admin);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle-admin', $admin))
            ->assertSessionHas('error');

        $this->assertTrue($admin->refresh()->is_admin);
    }

    public function test_non_admins_cannot_promote_anyone(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->patch(route('admin.users.toggle-admin', $user))
            ->assertForbidden();

        $this->assertFalse($user->refresh()->is_admin);
    }

    public function test_users_cannot_make_themselves_admin_through_their_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch(route('profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => '1',
        ]);

        $this->assertFalse($user->refresh()->is_admin);
    }
}
