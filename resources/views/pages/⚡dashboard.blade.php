<?php

use Livewire\Component;

new class extends Component
{
  public $user;

  public function mount()
  {
    $this->user = auth()->user();
  }
};
?>

<div>
  Bienvenido {{ $user->name }}, los roles que tienes asignados son los siguientes:
  <ul>
    @foreach($user->roles as $role)
      <li>{{ $role->name }}</li>
    @endforeach
  </ul>
</div>