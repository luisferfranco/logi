<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts.empty')]
class extends Component
{
    public $token;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public $statusMessage = null;
    public $statusType = null;

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ], function (User $user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            Auth::login($user);
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('dashboard');
        }

        $this->statusMessage = __($status);
        $this->statusType = 'error';
    }
};
?>

<div class="relative bg-base-100 rounded-lg max-w-lg shadow-lg w-full" x-data x-init="$nextTick(() =&gt; $refs.emailInput?.focus())">
  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-base-100 p-4 rounded-lg">
    <img src="/img/ferti-v.svg" class="h-24 w-auto">
  </div>

  <div class="p-8 pt-18">
    <h1 class="text-2xl font-bold mb-4">Restablecer contraseña</h1>

    @if ($statusMessage)
      <div class="mb-4 p-3 rounded-md {{ $statusType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800' }}">
        <p class="text-sm">{{ $statusMessage }}</p>
      </div>
    @endif

    <form wire:submit.prevent="resetPassword" class="space-y-3">
      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Correo electrónico</label>
        <x-input
          type="email"
          wire:model.defer="email"
          x-ref="emailInput"
          class="outline-none!"
          required
        />
        @error('email') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Contraseña</label>
        <x-input
          type="password"
          wire:model.defer="password"
          class="outline-none!"
          required
        />
        @error('password') <p class="text-sm text-error mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="text-xs font-bold uppercase tracking-wide">Confirmar contraseña</label>
        <x-input
          type="password"
          wire:model.defer="password_confirmation"
          class="outline-none!"
          required
        />
      </div>

      <x-button
        type="submit"
        label="Restablecer contraseña"
        class="btn-primary uppercase tracking-widest w-full"
        wire:loading.attr="disabled"
        wire:target="resetPassword"
      />
    </form>
  </div>
</div>
