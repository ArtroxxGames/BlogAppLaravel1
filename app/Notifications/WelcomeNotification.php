<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('¡Bienvenido/a a '.config('app.name').'!')
            ->greeting('¡Hola, '.$notifiable->name.'!')
            ->line('Gracias por unirte. Ya puedes leer, comentar y valorar artículos, o empezar a escribir los tuyos.')
            ->action('Escribir mi primer artículo', route('dashboard.articles.create'))
            ->line('¡Nos alegra tenerte por aquí!');
    }
}
