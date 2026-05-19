<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification
{
    use Queueable;

    public string $token;

    public Carbon $expiresAt;

    public function __construct(string $token, Carbon $expiresAt)
    {
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/invitation/accept?code='.$this->token);

        return (new MailMessage)
            ->subject('Invitación a Logi')
            ->greeting('Hola '.$notifiable->name)
            ->line('Has sido invitado a la plataforma. Haz clic en el enlace de abajo para completar tu registro.')
            ->action('Aceptar invitación', $url)
            ->line('Este enlace expirará el '.$this->expiresAt->toDayDateTimeString().'.')
            ->line('Si no esperabas esta invitación, ignora este correo.');
    }
}
