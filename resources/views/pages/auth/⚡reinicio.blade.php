<?php

use Livewire\Component;
use Mary\Traits\Toast;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

new
#[Layout('layouts.auth')]
class extends Component
{
  use Toast;

  public $token, $password, $password_confirmation, $user;

  public function mount($token)
  {
  if (auth()->check()) {
    return redirect()->route('dashboard');
  }
  $this->token = $token;
  $this->user = User::where('token_recuperacion', $token)
    ->where('token_recuperacion_expira', '>', now())
    ->first();
  }

  public function resetPassword() {
    $this->validate([
      'password' => [
        'required',
        'string',
        'max:16',
        // Escribimos la ruta completa de la regla de validación
        Password::min(8)
          ->mixedCase()
          ->numbers()
          ->symbols(),
      ],
    ]);

    if (!$this->user) {
      abort(404);
    }

    $this->user->password = bcrypt($this->password);
    $this->user->token_recuperacion = null;
    $this->user->token_recuperacion_expira = null;
    $this->user->save();

    Auth::loginUsingId($this->user->id);

    $this->success(
      title: '¡Contraseña Reiniciada!',
      description: 'Tu contraseña ha sido actualizada exitosamente. Ahora puedes iniciar sesión.',
      timeout: 3000,
      icon: 'o-check',
      redirectTo: route('dashboard'),
    );
  }
};
?>

<div>
  @if ($user)
    <p class="font-bold text-2xl">Reiniciar Contraseña</p>
    <p class="text-sm text-base-content/50">Ingresa tu nueva contraseña para tu cuenta.</p>

    <form wire:submit='resetPassword' class="space-y-4 mt-6">
      <div>
        <p class="font-bold text-xs tracking-widest uppercase">Nueva Contraseña</p>
        <x-input
          type="password"
          wire:model="password"
          required
          class="outline-none!"
          />
      </div>

      <div>
        <p class="font-bold text-xs tracking-widest uppercase">Confirmar Nueva Contraseña</p>
        <x-input
          type="password"
          wire:model="password_confirmation"
          required
          class="outline-none!"
          />
      </div>

      <x-button
        type="submit"
        class="w-full btn-primary"
        label="Reiniciar Contraseña"
        />
    </form>
  @else
    <x-alert
      class="alert-error"
      title="¡Token Inválido!"
      icon="o-hand-thumb-down"
      description="El enlace de reinicio no es válido o ha expirado."
      />
    <div class="mt-4">
      <x-button
        label="Solicitar Nuevo Enlace"
        class="btn-primary w-full"
        link="{{ route('password.request') }}"
        />
    </div>
  @endif
</div>