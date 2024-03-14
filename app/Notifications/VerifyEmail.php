<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private User $user, private string $name, private string $token)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $message = new MailMessage();
        $message->greeting('Hola ' . $this->user->username . '! Bienvenido ' . $this->name . '.');
        $message->line('Haga clic en el enlace a continuación para verificar su dirección de correo electrónico.');
        $message->action('Verificar E-mail', url('/auth/verify/' . $this->token));
        $message->line('Si no ha creado esta cuenta, por favor contáctenos ' . $this->name . '.');

        return $message;
    }
}
