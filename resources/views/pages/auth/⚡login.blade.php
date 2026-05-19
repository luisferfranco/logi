<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.empty')]
class extends Component
{
  public $email;
  public $password;
  public $remember = false;

  public function login()
  {
    // Validate the input
    $this->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (auth()->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
      return redirect()->intended('/dashboard');
    } else {
      $this->addError('email', 'Credenciales inválidas. Por favor, inténtelo de nuevo.');
    }
  }

}
?>

<div class="relative bg-base-100 rounded-lg max-w-lg shadow-lg w-full">
  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-base-100 p-4 rounded-lg">
    <img src="/img/ferti-v.svg" class="h-24 w-auto">
  </div>
  <div class="p-8 pt-18">
    <h1 class="text-2xl font-bold mb-4">Bienvenido nuevamente</h1>
    <p class="mb-2 text-gray-600">Inicie sesión en su cuenta</p>
    <form wire:submit='login' class="space-y-2">
      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Correo electrónico</label>
        <x-input
          type="email"
          wire:model="email"
          class="outline-none!"
          required
          />
      </div>

      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Contraseña</label>
        <x-input
          type="password"
          wire:model="password"
          class="outline-none!"
          required
          />
      </div>

      <div class="flex justify-between items-center">
        <div>
          <x-checkbox
            label="Recordarme (no cerrar sesión)"
            wire:model="remember"
            />
        </div>
        <div>
          <a
            wire:navigate
            href="{{ route('recover') }}"
            class="text-sm text-primary hover:underline"
            >
            ¿Olvidó su contraseña?
          </a>
        </div>
      </div>

      <x-button
        type="submit"
        label="Iniciar sesión"
        class="btn-primary uppercase tracking-widest w-full"
        />
    </form>
  </div>
</div>