<?php

namespace Pterodactyl\Notifications;

use Pterodactyl\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MailTested extends Notification
{
    public function __construct(private User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject('Mensage de texto de jexactyl')
            ->greeting('Hola ' . $this->user->name . '!')
            ->line('Este es un testo de sistema de E-mail Jexactyl.!');
    }
}
