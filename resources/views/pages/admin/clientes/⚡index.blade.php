<?php

use Livewire\Component;

new class extends Component
{
  public $clientes;
  public $headers;

  public function mount() {
    if (!auth()->user()->can('index clientes')) {
      abort(403);
    }

    $this->clientes = \App\Models\Cliente::all();

    $this->headers = [
      ['key' => 'id', 'label' => 'ID'],
      ['key' => 'nombre', 'label' => 'Cliente'],
      ['key' => 'user.nombre', 'label' => 'Administrador'],
      ['key' => 'estado', 'label' => 'Estado'],
    ];
  }
};
?>

<x-card title="Gestión de Clientes" class="bg-base-100">

  @can('create clientes')
    <x-button
      icon="o-plus-circle"
      class="btn-sm btn-primary"
      link="{{ route('admin.clientes.create') }}"
      label="Nuevo Cliente"
      />
  @endcan

  @if ($errors->any())
    <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
      <strong class="font-bold">¡Ups! Por favor corrige los siguientes errores:</strong>
      <ul class="mt-2 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif


  <x-table
    :headers="$headers"
    :rows="$clientes"
    >
    @scope('cell_nombre', $c)
      <p class="font-bold">{{ $c->nombre }}</p>
      <p class="text-xs text-base-content/50">{{ $c->direccion }}</p>
      <p class="text-xs text-base-content/50">{{ $c->telefono }}</p>
    @endscope

    @scope('cell_user.nombre', $c)
      @if ($c->user)
        <span class="font-bold">{{ $c->user->nombre }}</span><br>
        <span class="text-sm text-gray-500">{{ $c->user->email }}</span>
      @else
        <x-badge class="badge-sm badge-neutral uppercase tracking-widest" value="Sin admin" />
      @endif
    @endscope

    @scope('cell_estado', $c)
      <x-badge
        class="badge-{{ $c->estado->color() }}"
        value="{{ $c->estado->label() }}"
        />
    @endscope
  </x-table>
</x-card>