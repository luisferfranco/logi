<?php

use Livewire\Component;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

new class extends Component
{
  use Toast;

  public $role;
  public string $nombreRol = '';
  public array $permisosSeleccionados = [];
  public array $acciones = ['index', 'show', 'create', 'update', 'delete'];

  public function mount(Role $role): void
  {
    if (!auth()->user()->can('edit roles')) {
      abort(403);
    }

    $this->role = $role;
    $this->nombreRol = $role->name;

    // Cargamos los NOMBRES de los permisos que ya tiene el rol asignados
    $this->permisosSeleccionados = $this->role->permissions()->pluck('name')->toArray();
  }

  public function getPermisosPorFamiliaProperty()
  {
    $todosLosPermisos = Permission::all();

    return $todosLosPermisos->groupBy(function ($permiso) {
      $partes = explode(' ', $permiso->name);
      return end($partes);
    })->map(function ($permisosDeFamilia) {
      return $permisosDeFamilia->sortBy(function ($permiso) {
        $partes = explode(' ', $permiso->name);
        $accionOriginal = $partes[0];
        $posicion = array_search($accionOriginal, $this->acciones);
        return $posicion !== false ? $posicion : 99;
      });
    });
  }

  public function save(): void
  {
    // Validamos que el nombre sea obligatorio y único, ignorando el ID del rol actual
    $this->validate([
      'nombreRol' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
    ]);

    // Actualizamos el nombre del rol
    $this->role->update([
      'name' => strtolower($this->nombreRol),
    ]);

    // Sincronizamos los permisos usando sus nombres
    $this->role->syncPermissions($this->permisosSeleccionados);

    $this->success(
      title: 'Rol actualizado',
      description: "El rol '{$this->role->name}' ha sido actualizado exitosamente.",
      icon: 'o-check-circle',
      redirectTo: route('admin.roles.index')
    );
  }
};
?>

<x-card class="bg-base-100" title="Edición del Rol">

  @include('pages.admin.roles._form', [
      'textoBoton' => 'Guardar Cambios'
    ])
</x-card>