<?php

use Livewire\Component;
use Spatie\Permission\Models\Role;

new class extends Component
{
  public $roles;

  public function mount()
  {
    if (! auth()->user()->can('index roles')) {
      abort(403);
    }

    $this->roles = Role::where('name', '!=', 'Super Admin')
      ->with('permissions')
      ->orderBy('name')
      ->get();
  }
};
?>

<x-card class="bg-base-100" title="Roles">

  <x-button
    link="{{ route('admin.roles.create') }}"
    class="btn-primary mb-4"
    label="NUEVO"
    icon="o-plus-circle"
    />

  @if ($roles->isEmpty())
    <p class="text-center text-base-content/50">No hay roles disponibles.</p>
  @else
    <div class="grid grid-cols-6 gap-2">
      @foreach ($roles as $role)
        <div class="col-span-5">
          <p class="text-lg font-bold">{{ $role->name }}</p>
          <p class="text-xs text-base-content/75">Permisos:</p>
          <p class="text-xs text-base-content/50">
            @if ($role->permissions->isEmpty())
              <span class="text-base-content/50">Sin permisos asignados</span>
            @else
              @foreach ($role->permissions as $p)
                <x-badge
                  class="badge-sm badge-neutral mr-1"
                  value="{{ $p->name }}"
                  />
              @endforeach
            @endif
          </p>
        </div>

        <div class="flex flex-col justify-center items-end">
          <x-button
            link="{{ route('admin.roles.edit', $role) }}"
            class="btn-primary btn-square"
            icon="o-pencil-square"
            />
        </div>
      @endforeach
    </div>
  @endif
</x-card>