<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new
#[Layout('layouts.auth')]
class extends Component
{
  public $email, $password;
  public $remember = true;

  public function mount()
  {
    if (auth()->check()) {
      return redirect()->route('dashboard');
    }

    $this->email = request()->query('email', null);
    if (!$this->email) {
      $this->redirectRoute('login');
    }
  }

  public function login() {
    $this->validate([
      'password' => 'required|string',
    ]);

    if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
      $this->redirectRoute('dashboard');
    } else {
      $this->addError('password', 'La combinación de usuario y password no existe en el sistema');
    }
  }
};
?>

<div>
  <p class="font-bold text-2xl">Reinicia tu Contraseña</p>
  <p class="text-sm text-base-content/50">Ingresa tu nueva contraseña para tu cuenta.</p>

  <form wire:submit='login' class="space-y-4 mt-6">
    <div>
      <p class="font-bold text-xs tracking-widest uppercase">Correo Electrónico</p>
      <x-input
        wire:model="email"
        disabled
        class="outline-none!"
        />
    </div>

    <div>
      <p class="font-bold text-xs tracking-widest uppercase">Contraseña</p>
      <x-input
        type="password"
        wire:model="password"
        required
        class="outline-none!"
        />
    </div>
    <x-checkbox
      wire:model="remember"
      label="No cerrar la sesión"
      />
    <x-button label="Login" class="btn-primary w-full" type="submit" />
  </form>

  <p class="mt-6">
    Si olvidaste tu password, por favor haz click <a wire:navigate class="hover:underline text-info" href="{{ route('password.request') }}">aquí</a>.
  </p>
</div>