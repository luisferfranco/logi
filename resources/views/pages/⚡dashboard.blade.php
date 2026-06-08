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

<div class="space-y-4">
  @if ($user->avatar)
    <div class="flex items-center justify-center">
      <img src="{{ $user->avatar }}" alt="Avatar" class="w-24 h-24 rounded-full">
    </div>
  @endif
  @if ($user->external_id)
    <p class="text-xl font-bold text-center">{{ $user->external_id }}</p>
  @endif
  <p>Aquí podrás ver un resumen de tus actividades, estadísticas y más.</p>
  <p>¡Explora las diferentes secciones para aprovechar al máximo Blat!</p>
  <x-button
    wire:click='logout'
    class="btn-error"
    label="Logout"
    />
</div>