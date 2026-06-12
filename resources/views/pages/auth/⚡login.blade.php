<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Enum\EstadoUsuario;

new
#[Layout('layouts.auth')]
class extends Component
{
  public $email;
  public $bloqueado = false;

  public function mount() {
    if (auth()->check()) {
      return redirect()->route('dashboard');
    }
  }

  public function login()
  {
    // Si es un usuario con cuenta de Fertinal, usar
    // Laravel Socialite para autenticar con Google

    $user = User::where('email', $this->email)->first();
    if ($user && $user->estado == EstadoUsuario::BLOQUEADO) {
      $this->bloqueado = true;
      return;
    }

    if (str_ends_with($this->email, '@fertinal.com')) {
      $this->redirect('/login-google');
      return;
    }

    $this->redirectRoute('password', ['email' => $this->email]);
  }
};
?>

<div>
  <p class="font-bold text-2xl">Iniciar Sesión</p>
  <p class="text-sm text-base-content/50">Ingresa tu correo electrónico para iniciar sesión en tu cuenta.</p>

  @if ($bloqueado)
    <x-alert
      title="Cuenta Bloqueada"
      description="Tu cuenta ha sido bloqueada. Por favor, contacta al administrador para más información."
      icon="o-lock-closed"
      class="alert-error my-4"
      />
  @endif

  <form wire:submit="login" class="space-y-4 mt-6">

    <div>
      <p class="font-bold text-xs tracking-widest uppercase">Correo Electrónico</p>
      <x-input
        type="email"
        wire:model="email"
        required
        class="outline-none!"
        spinner
        />
    </div>
    <x-button label="Login" class="btn-primary w-full" type="submit" />
  </form>
</div>