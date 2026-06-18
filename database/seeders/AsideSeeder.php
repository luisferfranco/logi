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
        'icono'                         => 'tabler.users',
        'ruta'                          => 'admin.users.index',
        'orden'                         => 10,
      ],

      // Viajes --------------------------------------------------------
      [
        'id'                            => 20,
        'nombre'                        => 'Viajes',
        'permission_name'               => 'index viajes',
        'icono'                         => 'tabler.truck-delivery',
        'orden'                         => 20,
      ],
      [
        'id'                            => 21,
        'nombre'                        => 'Cargar',
        'permission_name'               => 'create viajes',
        'icono'                         => 'tabler.world-download',
        'ruta'                          => 'construccion',
        'orden'                         => 10,
        'parent_id'                     => 20,
      ],
      [
        'id'                            => 22,
        'nombre'                        => 'Todos',
        'permission_name'               => 'index viajes',
        'icono'                         => 'tabler.truck',
        'ruta'                          => 'construccion',
        'orden'                         => 20,
        'parent_id'                     => 20,
      ],
      [
        'id'                            => 23,
        'nombre'                        => 'Asignados',
        'permission_name'               => 'index viajes',
        'icono'                         => 'tabler.clipboard-text',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 20,
      ],

      // Catálogos -----------------------------------------------------
      [
        'id'                            => 30,
        'nombre'                        => 'Catálogos',
        'permission_name'               => 'index catalogos',
        'icono'                         => 'tabler.vocabulary',
        'orden'                         => 30,
      ],
      [
        'id'                            => 31,
        'nombre'                        => 'Productos',
        'permission_name'               => 'index catalogos',
        'icono'                         => 'tabler.barcode',
        'ruta'                          => 'construccion',
        'orden'                         => 10,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 32,
        'nombre'                        => 'Clientes',
        'permission_name'               => 'index clientes',
        'icono'                         => 'tabler.user-bolt',
        'ruta'                          => 'construccion',
        'orden'                         => 20,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 32,
        'nombre'                        => 'Presentaciones',
        'permission_name'               => 'index presentaciones',
        'icono'                         => 'tabler.category-2',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 33,
        'nombre'                        => 'Operadores',
        'permission_name'               => 'index operadores',
        'icono'                         => 'tabler.id-badge-2',
        'ruta'                          => 'construccion',
        'orden'                         => 40,
        'parent_id'                     => 30,
      ],
      [
        'id'                            => 34,
        'nombre'                        => 'Unidades',
        'permission_name'               => 'index unidades',
        'icono'                         => 'tabler.truck',
        'ruta'                          => 'construccion',
        'orden'                         => 50,
        'parent_id'                     => 30,
      ],

      // Roles ---------------------------------------------------------
      [
        'id'                            => 40,
        'nombre'                        => 'Roles',
        'permission_name'               => 'index roles',
        'icono'                         => 'tabler.circles',
        'orden'                         => 40,
      ],
      [
        'id'                            => 41,
        'nombre'                        => 'Crear',
        'permission_name'               => 'create roles',
        'icono'                         => 'tabler.user-circle',
        'ruta'                          => 'admin.roles.create',
        'orden'                         => 10,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 42,
        'nombre'                        => 'Editar',
        'permission_name'               => 'index roles',
        'icono'                         => 'tabler.edit',
        'ruta'                          => 'admin.roles.index',
        'orden'                         => 20,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 43,
        'nombre'                        => 'Formulario',
        'permission_name'               => 'index formulario',
        'icono'                         => 'tabler.clipboard-list',
        'ruta'                          => 'construccion',
        'orden'                         => 30,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 44,
        'nombre'                        => 'Permiso',
        'permission_name'               => 'index permisos',
        'icono'                         => 'tabler.clipboard-check',
        'ruta'                          => 'construccion',
        'orden'                         => 40,
        'parent_id'                     => 40,
      ],
      [
        'id'                            => 45,
        'nombre'                        => 'CEDAS',
        'permission_name'               => 'index cedas',
        'icono'                         => 'tabler.building-warehouse',
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
