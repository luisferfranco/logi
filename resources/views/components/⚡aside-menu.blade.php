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
        ->pluck('name')
        ->toArray();
    } else {
      $permissions = auth()->user()
        ->getAllPermissions()
        ->filter(fn($perm) => str_starts_with($perm->name, 'index '))
        ->pluck('name')
        ->toArray();
    }

    $this->asideItems = AsideItem::whereIn('permission_name', $permissions)
      ->where('parent_id', null)
      ->orderBy('orden')
      ->get();
  }
};
?>

<div>
  @foreach ($asideItems as $item)
    <livewire:aside-item :item="$item" wire:key="menu-{{ $item->id }}" />
  @endforeach
</div>