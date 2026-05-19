<?php

use Livewire\Component;

new class extends Component
{
  public $users;
  public $headers;

  public function mount() {
    if (!(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))) {
      abort(403);
    }
    $this->users = \App\Models\User::all();
    $this->headers = [
      ['key' => 'id', 'label' => 'ID', 'class' => 'w-1'],
      ['key' => 'name', 'label' => 'Nombre'],
      ['key' => 'status', 'label' => 'Estado'],
    ];
  }
};
?>

<div>
  <x-button
    icon="tabler.user-plus"
    class="btn-primary uppercase tracking-widest mb-4"
    label="Crear un nuevo usuario"
    />

  <x-table :headers="$headers" :rows="$users" class="bg-base-100">
    @scope('cell_name', $user)
      <div>{{ $user->name }}</div>
      <div class="text-base-content/50 text-sm">{{ $user->email }}</div>
    @endscope

    @scope('cell_status', $user)
      <x-badge
        class="badge-sm badge-{{ $user->status->color() }} uppercase tracking-wide"
        value="{{ $user->status->label() }}"
        />
    @endscope
  </x-table>
</div>