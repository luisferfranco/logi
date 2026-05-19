<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.empty')]
class extends Component
{
  public $email = '';
  public $statusMessage = null;
  public $statusType = null; // 'success' | 'error'

  public function recover()
  {
    $this->validate([
      'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(['email' => $this->email]);

    if ($status === Password::RESET_LINK_SENT) {
      $this->statusMessage = 'Si existe una cuenta con ese correo electrónico, se han enviado las instrucciones para recuperar la contraseña.';
      $this->statusType = 'success';
      $this->email = '';
    } else {
      $this->statusMessage = 'No se pudo enviar el correo de recuperación. Intenta nuevamente más tarde.';
      $this->statusType = 'error';
    }
  }
};
?>

<div class="relative bg-base-100 rounded-lg max-w-lg shadow-lg w-full" x-data x-init="$nextTick(() =&gt; $refs.emailInput?.focus())">
  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-base-100 p-4 rounded-lg">
    <img src="/img/ferti-v.svg" class="h-24 w-auto">
  </div>
  <div class="p-8 pt-18">
    <h1 class="text-2xl font-bold mb-4">Recuperar contraseña</h1>
    <p class="mb-2 text-gray-600">Ingrese su correo electrónico para recibir instrucciones de recuperación</p>

    @if ($statusMessage)
      <div class="mb-4 p-3 rounded-md {{ $statusType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
        <p class="text-sm">{{ $statusMessage }}</p>
      </div>
    @endif

    <form wire:submit.prevent="recover" class="space-y-2">
      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Correo electrónico</label>
        <x-input
          type="email"
          wire:model.defer="email"
          x-ref="emailInput"
          class="outline-none!"
          required
          aria-label="Correo electrónico"
          />
        @error('email') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
      </div>

      <x-button
        type="submit"
        label="Recuperar contraseña"
        class="btn-primary uppercase tracking-widest w-full"
        wire:loading.attr="disabled"
        wire:target="recover"
        />
      <p class="text-base-content text-xs">Se enviará un correo electrónico con las instrucciones para recuperar su contraseña.</p>
    </form>
  </div>
</div>