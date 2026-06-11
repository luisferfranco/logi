<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NotificacionRecuperacion extends Notification
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
      ->subject('Fertinal :: Reinicio de la Contraseña')
      ->greeting('Fertinal :: Reinicio de la Contraseña')
      ->line('Has solicitado reiniciar tu contraseña.')
      ->action('Cambiar Contraseña', url('/reinicio/' . $this->user->token_recuperacion))
      ->line('Si no solicitaste este reinicio, puedes ignorar este correo.')
      ->line('Este enlace de reinicio expirará el ' . $this->user->token_recuperacion_expira->format('d/m/Y H:i') . '.');
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
