<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Mary\Traits\Toast;
use App\Notifications\NotificacionRecuperacion;

new
#[Layout('layouts.auth')]
class extends Component
{
  use Toast;
  public $email;

  public function mount()
  {
    if (auth()->check()) {
      return redirect()->route('dashboard');
    }
  }

  public function recover() {
    $this->validate([
      'email' => 'required|email',
    ]);

    if ($user = User::where('email', $this->email)->first()) {
      $user->token_recuperacion = Str::random(32);
      $user->token_recuperacion_expira = now()->addMinutes(60);
      $user->save();

      // Aquí deberías enviar el correo con el enlace de recuperación
      $user->notify(new NotificacionRecuperacion($user));
    }

    $this->success(
      title: '¡Correo Enviado!',
      description: 'Si el correo existe en nuestro sistema, recibirás un enlace de recuperación.',
      timeout: 3000,
      icon: 'o-envelope',
      redirectTo: route('login'),
    );
  }
};
?>

<div>
  <h1 class="text-2xl font-bold mb-4">Recuperar Contraseña</h1>
  <p class="mb-4">Ingresa tu correo electrónico para recibir un enlace de recuperación de contraseña.</p>

  <form wire:submit="recover" class="space-y-4">
    <x-input
      type="email"
      id="email"
      wire:model="email"
      required
      autofocus
      placeholder="Correo Electrónico"
      label="Correo Electrónico"
      class="outline-none"
      />

    <x-button
      type="submit"
      class="w-full btn-primary"
      label="Enviar Enlace de Recuperación"
      spinner
      />
  </form>
</div>