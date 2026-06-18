<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsideItem;

class AsideSeeder extends Seeder
{
  public function run(): void
  {
    $items = [
      // Usuarios ------------------------------------------------------
      [
        'id'                            => 10,
        'nombre'                        => 'Usuarios',
        'permission_name'               => 'index usuarios',
        'icono'                         => 'phosphor.users-duotone',
        'ruta'                          => 'admin.users.index',
        'orden'                         => 10,
      ],

      // Viajes --------------------------------------------------------
      [
        'id'                            => 20,
        'nombre'                        => 'Viajes',
        'permission_name'               => 'index viajes',
        'icono'                         => 'phosphor.truck-trailer-duotone',
        'orden'                         => 20,
      ],
      [
        'id'                            => 21,
        'nombre'                        => 'Cargar',
        'permission_name'               => 'create viajes',
        'icono'                         => 'phosphor.box-arrow-down-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 10,
        'parent_id'                     => 20,
      ],
      [
        'id'                            => 22,
        'nombre'                        => 'Todos',
        'permission_name'               => 'index viajes',
        'icono'                         => 'phosphor.road-horizon-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 20,
        'parent_id'                     => 20,
      ],
      [
        'id'                            => 23,
        'nombre'                        => 'Asignados',
        'permission_name'               => 'index viajes',
        'icono'                         => 'phosphor.clipboard-text-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 20,
      ],

      // Catálogos -----------------------------------------------------
      [
        'id'                            => 30,
        'nombre'                        => 'Catálogos',
        'permission_name'               => 'index catalogos',
        'icono'                         => 'phosphor.book-duotone',
        'orden'                         => 30,
      ],
      [
        'id'                            => 31,
        'nombre'                        => 'Productos',
        'permission_name'               => 'index catalogos',
        'icono'                         => 'phosphor.barcode-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 10,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 32,
        'nombre'                        => 'Clientes',
        'permission_name'               => 'index clientes',
        'icono'                         => 'phosphor.user-circle-plus-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 20,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 32,
        'nombre'                        => 'Presentaciones',
        'permission_name'               => 'index presentaciones',
        'icono'                         => 'phosphor.presentation-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 33,
        'nombre'                        => 'Operadores',
        'permission_name'               => 'index operadores',
        'icono'                         => 'phosphor.identification-card-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 40,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 34,
        'nombre'                        => 'Unidades',
        'permission_name'               => 'index unidades',
        'icono'                         => 'phosphor.truck-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 50,
        'parent_id'                     => 30,
      ],

      // Roles ---------------------------------------------------------
      [
        'id'                            => 40,
        'nombre'                        => 'Roles',
        'permission_name'               => 'index roles',
        'icono'                         => 'phosphor.users-four-duotone',
        'orden'                         => 40,
      ],
      [
        'id'                            => 41,
        'nombre'                        => 'Crear',
        'permission_name'               => 'create roles',
        'icono'                         => 'phosphor.user-circle-dashed-duotone',
        'ruta'                          => 'admin.roles.create',
        'orden'                         => 10,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 42,
        'nombre'                        => 'Editar',
        'permission_name'               => 'index roles',
        'icono'                         => 'phosphor.pencil-circle-duotone',
        'ruta'                          => 'admin.roles.index',
        'orden'                         => 20,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 43,
        'nombre'                        => 'Formulario',
        'permission_name'               => 'index formulario',
        'icono'                         => 'phosphor.clipboard-text-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 44,
        'nombre'                        => 'Permiso',
        'permission_name'               => 'index permisos',
        'icono'                         => 'phosphor.checks-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 40,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 45,
        'nombre'                        => 'CEDAS',
        'permission_name'               => 'index cedas',
        'icono'                         => 'phosphor.warehouse-duotone',
        'ruta'                          => 'construccion',
        'orden'                         => 50,
        'parent_id'                     => 40,
      ],
    ];

    foreach ($items as $item) {
      AsideItem::updateOrCreate([
        'id' => $item['id']
      ], [
        'nombre'          => $item['nombre'],
        'icono'           => $item['icono'],
        'ruta'            => $item['ruta'] ?? null,
        'permission_name' => $item['permission_name'] ?? null,
        'orden'           => $item['orden'],
        'parent_id'       => $item['parent_id'] ?? null,
      ]);
    }
  }
}
