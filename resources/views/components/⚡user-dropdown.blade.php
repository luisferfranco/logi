<?php

use Livewire\Component;

new class extends Component
{
  public $user;

  public function mount() {
    $this->user = auth()->user();
  }

  public function logout()
  {
    auth()->logout();
    return redirect()->route('login');
  }
};
?>

<x-dropdown>
  <x-slot:trigger>
    <img
      src="{{ auth()->user()->avatar_url }}"
      class="w-8 h-8 rounded-full mr-2 cursor-pointer"
      />
  </x-slot:trigger>

  <x-menu-item
    link="{{ route('profile') }}"
    icon="tabler.user"
    title="Mi Pefil"
    />
  <x-menu-item
    wire:click="logout"
    icon="tabler.logout"
    title="Salir del sistema"
    />
</x-dropdown>
