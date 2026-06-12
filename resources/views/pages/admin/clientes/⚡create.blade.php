<?php

use Livewire\Component;
use App\Models\User;
use Mary\Traits\Toast;

new class extends Component
{
  use Toast;

  public $rfc;
  public $nombre;
  public $telefono;
  public $direccion;
  public $users, $user_id;

  public function mount() {
    $this->users = User::where('email', 'not like', '%@fertinal.com')
      ->orderBy('nombre')
      ->get();
  }

  public function save() {
    $this->validate([
      'rfc'       => 'required|unique:clientes,rfc',
      'nombre'    => 'required',
      'telefono'  => 'required',
      'direccion' => 'required',
    ]);

    \App\Models\Cliente::create([
      'rfc'       => $this->rfc,
      'nombre'    => $this->nombre,
      'telefono'  => $this->telefono,
      'direccion' => $this->direccion,
      'user_id'   => $this->user_id,
    ]);

    $this->success(
      title:        'Cliente creado',
      description:  'El cliente ha sido creado exitosamente.',
      timeout:      5000,
      icon:         'o-check-circle',
    );
  }
};
?>

<x-card title="Nuevo Cliente" class="bg-base-100">
  <p class="text-sm text-gray-500 mb-4">Crea un nuevo cliente para gestionar sus envíos y propuestas.</p>

  <x-form wire:submit='save'>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <x-input
          wire:model='rfc'
          label="RFC"
          placeholder="RFC (Ej: ABC123456789XYZ)"
          inline
          required
          />
      </div>

      <div class="md:col-span-3">
        <x-input
          wire:model="nombre"
          label="Nombre del Cliente"
          placeholder="Nombre del Cliente (Ej: Transportes XYZ S.A.)"
          inline
          required
          />
      </div>

      <div>
        <x-input
          wire:model="telefono"
          label="Teléfono"
          required
          inline
          placeholder="Teléfono (Ej: +54 9 11 1234-5678)"
          />
      </div>

      <div class="md:col-span-3">
        <x-input
          wire:model="direccion"
          label="Dirección"
          required
          inline
          placeholder="Ej: Av. Siempre Viva 123, Ciudad"
          />
      </div>

      <div>
        <x-select
          wire:model="user_id"
          label="Administrador del Cliente"
          :options="$users"
          option-value="id"
          option-label="nombre"
          placeholder="Selecciona un administrador para este cliente"
          inline
          >
        </x-select>
      </div>
    </div>

    <div class="flex justify-end">
      <x-button
        link="{{ route('admin.clientes.index') }}"
        class="btn-ghost mr-2"
        label="Cancelar"
        />
      <x-button
        type="submit"
        class="btn-primary"
        label="Crear Cliente"
        />
    </div>
  </x-form>
</x-card>