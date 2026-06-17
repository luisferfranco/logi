<?php

use Livewire\Component;
use App\Models\AsideItem;

new class extends Component
{
  public AsideItem $item;
  public $children;

  public function mount(AsideItem $item) {
    $this->item = $item;
    $this->children = collect();

    if ($item->parent_id == null) {
      $this->children = AsideItem::where('parent_id', $item->id)
        ->orderBy('orden')
        ->get();
    }

    info($this->children);
  }
};
?>

<div>
  @if ($item->parent_id == null && $item->ruta === null)
    <x-menu-sub
      title="{{ $item->nombre }}"
      icon="{{ $item->icono }}"
      >
      @foreach ($children as $child)
        <livewire:aside-item :item="$child" wire:key="menu-{{ $child->id }}" />
      @endforeach
    </x-menu-sub>
  @else
    <x-menu-item
      title="{{ $item->nombre }}"
      icon="{{ $item->icono }}"
      link="{{ $item->ruta == '#' ? '#' : route($item->ruta) }}"
      />
  @endif
</div>