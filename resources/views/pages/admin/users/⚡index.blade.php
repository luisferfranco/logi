<?php

use Livewire\Component;
use App\Enum\EstadoUsuario;
use Mary\Traits\Toast;

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
    if (!$user->can('gestionar usuarios')) {
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

  public function crear() {
    $this->validate([
      'nombre' => 'required',
      'email' => 'required|email|unique:users,email',
      'rfc' => 'required|regex:/^[A-ZÑ]{4}\d{6}[A-ZÑ0-9]{3}$/',
    ]);

    $user = \App\Models\User::create([
      'nombre' => $this->nombre,
      'email' => $this->email,
      'empleado' => $this->empleado,
      'rfc' => $this->rfc,
      'estado' => EstadoUsuario::PENDIENTE,
    ]);

    // Enviar la notificación de creación de cuenta por
    // correo electrónico

    // Resetear campos y cerrar modal
    $this->reset(['nombre', 'email', 'empleado', 'rfc']);
    $this->crearModal = false;

    // Refrescar lista de usuarios
    $this->users = \App\Models\User::all();
  }

  public function crearUsuario() {
    $this->reset(['nombre', 'email', 'empleado', 'rfc']);
    $this->crearModal = true;
  }
};
?>

<x-card>
  <x-modal wire:model="crearModal" class="backdrop-blur">
    <h1 class="text-xl font-bold">Crear un nuevo usuario</h1>
    <p class="text-base-content/50">Complete los campos para crear un nuevo usuario.</p>
    <form wire:submit='crear' class="space-y-2 mt-4">
      <x-input
        label="Nombre completo"
        wire:model="nombre"
        class=""
        required
        />

      <x-input
        label="Correo electrónico"
        type="email"
        wire:model="email"
        class=""
        required
        />

      <x-input
        label="Número de empleado"
        wire:model="empleado"
        class=""
        hint="Si se trata de un usuario de Fertinal, por favor introduce su número de empleado."
        />

      <x-input
        label="RFC"
        wire:model="rfc"
        class=""
        required
        />

      <div class="flex justify-end gap-2 mt-4">
        <x-button
          label="Cancelar"
          class="btn-ghost"
          @click="$set('crearModal', false)"
          />
        <x-button
          label="Crear usuario"
          class="btn-primary"
          type="submit"
          />
      </div>
    </form>
  </x-modal>

  <x-button
    wire:click="crearUsuario"
    label="Nuevo usuario"
    class="btn-primary mb-6"
    icon="o-plus-circle"
    />

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
      @if ($u->estado == EstadoUsuario::INACTIVO || $u->estado == EstadoUsuario::PENDIENTE)
        <x-button
          wire:click="invitar({{ $u }})"
          class="btn-square btn-info"
          icon="o-envelope"
          tooltip-left="Invitar"
          />
      @endif

      @if (($u->id !== auth()->id()) && ($u->estado == EstadoUsuario::ACTIVO || $u->estado == EstadoUsuario::BLOQUEADO))
        <x-button
          wire:click="bloqueo({{ $u }})"
          class="btn-square {{ $u->estado==EstadoUsuario::ACTIVO ? 'btn-error' : 'btn-success' }}"
          icon="{{ $u->estado==EstadoUsuario::ACTIVO ? 'o-lock-closed' : 'o-lock-open' }}"
          tooltip-left="{{ $u->estado==EstadoUsuario::ACTIVO ? 'Bloquear' : 'Desbloquear' }}"
          />
      @endif
    @endscope
  </x-table>
</x-card>