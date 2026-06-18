<?php

use Livewire\Component;
use App\Enum\EstadoUsuario;
use Mary\Traits\Toast;
use App\Models\User;
use Illuminate\Support\Facades\DB;

new class extends Component
{
  use Toast;

  public $user;
  public $users;
  public $headers;

  // Modal para crear usuarios
  public $crearModal = false;
  public $nombre, $email, $empleado, $rfc;

  public function mount() {
    $user = auth()->user();
    if (!$user->can('index usuarios')) {
      abort(403);
    }

    $this->headers = [
      ['key' => 'id', 'label' => 'ID'],
      ['key' => 'nombre', 'label' => 'Nombre'],
      ['key' => 'estado', 'label' => 'Estado'],
      ['key' => 'created_at', 'label' => 'Creación / Actualización'],
    ];
    $this->users = \App\Models\User::all();
  }

  public function invitar(User $user) {
    $user->update([
      'codigo_invitacion'     => \Str::random(40),
      'expiracion_invitacion' => now()->addDays(2),
    ]);
    $user->notify(new \App\Notifications\NotificacionInvitacion($user));
    $this->success(
      title: 'Invitación enviada',
      description: 'Se ha enviado una invitación por correo electrónico al usuario ' . $user->email . '.',
      timeout: 5000,
      icon: 'o-envelope',
    );
  }

  public function toggleBloqueo(User $user) {
    if ($user->estado == EstadoUsuario::ACTIVO) {
      $user->estado = EstadoUsuario::BLOQUEADO;
      $mensaje = 'Usuario bloqueado';
      $icono = 'o-lock-closed';

      // Cerrar todas las sesiones activas del usuario
      DB::table(config('session.table'))
        ->where('user_id', $user->id)
        ->delete();

      // Invalidar el remember me para que no se vuelva a reconectar automáticamente
      $user->remember_token = \Str::random(60);
    } else {
      $user->estado = EstadoUsuario::ACTIVO;
      $mensaje = 'Usuario desbloqueado';
      $icono = 'o-lock-open';
    }
    $user->save();

    // Refrescar lista de usuarios
    $this->users = User::all();

    $this->success(
      title: $mensaje,
      description: 'El usuario ' . $user->email . ' ha sido actualizado.',
      timeout: 5000,
      icon: $icono,
    );
  }
};
?>

<x-card class="bg-base-100" title="Usuarios" shadow separator>

  @can('create usuarios')
    <x-button
      link="{{ route('admin.users.create') }}"
      label="Nuevo usuario"
      class="btn-primary mb-6"
      icon="phosphor.plus-circle-duotone"
      />
  @endcan

  <x-table
    :headers="$headers"
    :rows="$users"
    >
    @scope('cell_nombre', $u)
      <x-avatar
        :image="$u->avatar"
        :title="$u->nombre"
        :subtitle="$u->email"
        class="w-8 h-8"
        />
    @endscope

    @scope('cell_estado', $u)
      <x-badge
        value="{{ $u->estado->label() }}"
        class="badge-{{ $u->estado->color() }}"
        />
    @endscope

    @scope('actions', $u)
      @can('update usuarios')
        <div class="flex items-center gap-1">
          <x-button
            link="{{ route('admin.users.edit', $u) }}"
            class="btn-square btn-neutral"
            icon="phosphor.pen-duotone"
            tooltip-left="Editar"
            />

          @if ($u->estado == EstadoUsuario::INACTIVO || $u->estado == EstadoUsuario::PENDIENTE)
            <x-button
              wire:click="invitar({{ $u }})"
              class="btn-square btn-info"
              icon="phosphor.envelope-duotone"
              tooltip-left="Invitar"
              spinner
              />
          @endif

          @if (($u->id !== auth()->id()) && ($u->estado == EstadoUsuario::ACTIVO || $u->estado == EstadoUsuario::BLOQUEADO))
            <x-button
              wire:click="toggleBloqueo({{ $u }})"
              class="btn-square btn-neutral"
              icon="{{ $u->estado==EstadoUsuario::ACTIVO ? 'phosphor.lock-duotone' : 'phosphor.lock-open-duotone' }}"
              tooltip-left="{{ $u->estado==EstadoUsuario::ACTIVO ? 'Bloquear' : 'Desbloquear' }}"
              spinner="toggleBloqueo({{ $u }})"
              />
          @endif
        </div>
      @endcan
    @endscope
  </x-table>
</x-card>