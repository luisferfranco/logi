<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

new
#[Layout('layouts.auth')]
class extends Component
{
  public bool $invalido = false;
  public bool $expirado = false;

  public $nombre, $email, $rfc, $password, $password_confirmation;

  public function mount($codigo)
  {
    $user = User::where('codigo_invitacion', $codigo)->first();
    if (!$user) {
      $this->invalido = true;
    } else if ($user->expiracion_invitacion->isPast()) {
      $this->expirado = true;
    } else {
      $this->nombre = $user->nombre;
      $this->email = $user->email;
      $this->rfc = $user->rfc;
    }
  }

  public function aceptar()
  {
    $user = User::where('email', $this->email)->first();
    if (!$user) {
      abort(404);
    }

    $this->validate([
      'nombre'    => 'required',
      'rfc'       => 'required|regex:/^[A-ZÑ]{4}\d{6}[A-ZÑ0-9]{3}$/',
      'password'  => 'required|confirmed|min:8',
    ]);

    $user->update([
      'rfc'                   => $this->rfc,
      'password'              => bcrypt($this->password),
      'estado'                => \App\Enum\EstadoUsuario::ACTIVO,
      'codigo_invitacion'     => null,
      'expiracion_invitacion' => null,
      'accepted_at'           => now(),
    ]);

    Auth::loginUsingId($user->id);

    $this->redirectRoute('dashboard');
  }
};
?>

<div>
  @if ($invalido)
    <x-alert
      class="alert-error my-6"
      title="Código de invitación inválido"
      message="El código de invitación que ingresaste no es válido."
      icon="s-hand-thumb-down"
      />
    <p class="mt-2 text-base-content/50">
      El código que intentaste usar es inválido. Por favor utiliza el del correo electrónico que recibiste o contacta al administrador para obtener uno nuevo.
    </p>
  @elseif ($expirado)
    <x-alert
      class="alert-error my-6"
      title="Código de invitación expirado"
      message="El código de invitación que ingresaste ha expirado."
      icon="s-hand-thumb-down"
      />
    <p class="mt-2 text-base-content/50">
      El código que intentaste usar ha expirado. Por favor contacta al administrador para obtener un nuevo código de invitación.
    </p>

  @else
    <h1 class="text-xl font-bold text-center mb-4">Bienvenido a nuestra plataforma</h1>
    <p class="text-center text-base-content/50 mb-2 text-sm">Tu código de invitación es válido. Por favor continúa con el proceso de registro para crear tu cuenta. Revisa y modifica los siguientes datos. Por favor elige una contraseña segura para tu cuenta.</p>
    <p class="text-center text-base-content/50 mb-2 text-sm">Una vez que completes el registro, podrás acceder a todas las funcionalidades de nuestra plataforma.</p>

    <form wire:submit='aceptar' class="space-y-4">
      <x-input
        label="Nombre completo"
        wire:model="nombre"
        required
        />

      <x-input
        label="Correo electrónico"
        wire:model="email"
        disabled
        />

      <x-input
        label="RFC"
        wire:model="rfc"
        required
        />

      <x-input
        label="Contraseña"
        type="password"
        wire:model="password"
        required
        />

      <x-input
        label="Confirmar contraseña"
        type="password"
        wire:model="password_confirmation"
        required
        />

      <x-button type="submit" class="btn-primary w-full">Crear cuenta</x-button>
    </form>
  @endif
</div>