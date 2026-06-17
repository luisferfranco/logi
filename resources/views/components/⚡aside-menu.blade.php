<?php

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use App\Models\AsideItem;

new class extends Component
{
  public $asideItems;

  public function mount() {

    if (auth()->user()->hasRole('Super Admin')) {
      $permissions = Permission::where('name', 'like', 'index %')
        ->pluck('id')
        ->toArray();
    } else {
      $permissions = auth()->user()
        ->getAllPermissions()
        ->filter(fn($perm) => str_starts_with($perm->name, 'index '))
        ->pluck('id')
        ->toArray();
    }

    $this->asideItems = AsideItem::whereIn('permission_id', $permissions)
      ->orderBy('orden')
      ->get();
  }
};
?>

<div>
  @foreach ($asideItems as $item)
    <x-menu-item
      :title="$item->nombre"
      :icon="$item->icono"
      :link="$item->ruta == '#' ? '#' : route($item->ruta)"
    />
  @endforeach
</div>