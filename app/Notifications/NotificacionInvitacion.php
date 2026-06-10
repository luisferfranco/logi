<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NotificacionInvitacion extends Notification
{
  use Queueable;

  public User $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
    return (new MailMessage)
      ->subject('Fertinal::Invitación a nuestra plataforma de Logística')
      ->greeting('Fertinal::Invitación a nuestra plataforma de Logística')
      ->line('Por favor acepta esta invitación para unirte a nuestra plataforma de logística.')
      ->action('Aceptar invitación', url('/invitacion/' . $this->user->codigo_invitacion))
      ->line('¡Gracias por usar nuestra aplicación!')
      ->line('Esta invitación expirará el ' . $this->user->expiracion_invitacion->format('d/m/Y H:i') . '.');
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
