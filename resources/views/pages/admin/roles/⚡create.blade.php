<?php

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Mary\Traits\Toast;
use Spatie\Permission\Models\Role;

new class extends Component
{
  use Toast;

  public $nombreRol = '';
  public array $permisosSeleccionados = [];
  public array $acciones = ['index', 'show', 'create', 'update', 'delete'];


  public function mount(): void
  {
    if (!auth()->user()->can('create roles')) {
      abort(403);
    }
  }

  public function getPermisosPorFamiliaProperty()
  {
    $todosLosPermisos = Permission::all();

    return $todosLosPermisos->groupBy(function ($permiso) {
        $partes = explode(' ', $permiso->name);
        return end($partes); // Extrae la familia (ej: user, planta)
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
      $this->validate([
        'nombreRol' => 'required|string|unique:roles,name|max:255',
      ], [
        'nombreRol.required' => 'El nombre del rol es obligatorio.',
        'nombreRol.unique' => 'Ya existe un rol con este nombre.',
      ]);

      $nuevoRol = Role::create([
        'name'        => strtolower($this->nombreRol),
        'guard_name'  => 'web'
      ]);

      if (!empty($this->permisosSeleccionados)) {
        $nuevoRol->syncPermissions($this->permisosSeleccionados);
      }

      $this->success(
        title: 'Rol creado',
        description: "El rol '{$nuevoRol->name}' ha sido creado exitosamente.",
        icon: 'o-check-circle',
        timeout: 5000,
        redirectTo: route('admin.roles.index')
      );

    }
};
?>

<x-card class="bg-base-100" title="Crear Rol">

  @include("pages.admin.roles._form", [
    'textoBoton' => 'Crear Rol'
  ])

</x-card>