<?php

use Livewire\Component;

new class extends Component
{
  public $user;
  public $users;
  public $headers;

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
};
?>

<x-card>
  <x-table
    :headers="$headers"
    :rows="$users"
    >
  </x-table>
</x-card>