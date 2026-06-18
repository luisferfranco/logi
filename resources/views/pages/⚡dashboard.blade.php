<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new class extends Component
{
  public $user;

  public function mount()
  {
    $this->user = auth()->user();
  }

  public function logout()
  {
    Auth::logout();
    return $this->RedirectRoute('home');
  }
};
?>

<x-card class="mt-12 relative">
  @if ($user->avatar)
    <div class="absolute -top-12 left-1/2 transform -translate-x-1/2">
      <img src="{{ $user->avatar }}" alt="Avatar" class="w-24 h-24 rounded-full">
    </div>
  @endif
  @if ($user->id_externo)
    <div class="absolute text-base-content/50 top-12 left-1/2 transform -translate-x-1/2 text-xl font-bold">{{ $user->id_externo }}</div>
  @endif
  <div class="mt-18 space-y-4">
    <div>Aquí podrás ver un resumen de tus actividades, estadísticas y más.</div>
    <div>¡Explora las diferentes secciones para aprovechar al máximo Blat!</div>
    <x-button
      wire:click='logout'
      class="btn-error"
      label="Logout"
      />
  </div>

  <div class="py-4 px-6 mt-6 rounded-xl bg-base-100">
    <x-icon
      name="phosphor.building-apartment-duotone"
      class="w-12 h-12"
      />
  </div>
</x-card>