<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class WelcomeNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_receive_a_welcome_notification(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        Notification::assertSentTo(User::firstWhere('email', 'ana@example.com'), WelcomeNotification::class);
    }
}
