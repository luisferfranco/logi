<?php

use Livewire\Component;
use App\Models\AsidePermission;

new class extends Component
{
  public $aside;

  public function mount() {
    $this->aside = AsidePermission::query()
      ->whereIn('permission_id',
        auth()->user()->getAllPermissions()->pluck('id'))
      ->orderBy('orden')
      ->get();
  }
};
?>

<div>
  @foreach ($aside as $item)
    <x-menu-item
      title="{{ $item->nombre }}"
      icon="{{ $item->icono }}"
      link="{{ $item->ruta == '#' ? '#' : route($item->ruta) }}"
      />
  @endforeach
</div>