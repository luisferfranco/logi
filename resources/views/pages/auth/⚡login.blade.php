<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new
#[Layout('layouts.auth')]
class extends Component
{
  public $email;

  public function mount() {
    if (auth()->check()) {
      return redirect()->route('dashboard');
    }
  }

  public function login()
  {
    // Si es un usuario con cuenta de Fertinal, usar
    // Laravel Socialite para autenticar con Google
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