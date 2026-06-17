<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Models\AsideItem;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application"s database.
   */
  public function run(): void
  {
    // Al ser un usuario fertinal, no es necesario conocer el password y se puede asignar uno aleatorio
    $user = User::updateOrCreate(
      [
        "email" => "lffrancoj@fertinal.com"
      ],
      [
      "nombre"    => "Luis Fernando Franco",
      "email"     => "lffrancoj@fertinal.com",
      "empleado"  => "10000341",
      "estado"    => "activo",
      "password"  => bcrypt(Str::random(16)),
    ]);

    $permisos = [
      [
        'familia' => 'roles',
        'ruta'    => 'admin.roles.index',
        'icono'   => 'o-finger-print',
        'orden'   => 10,
      ],
      [
        'familia' => 'usuarios',
        'ruta'    => 'admin.users.index',
        'icono'   => 'o-users',
        'orden'   => 15,
      ],

      [
        'familia' => 'clientes',
        'ruta'    => 'admin.clientes.index',
        'icono' => 'o-truck',
        'orden' => 20,
      ],
      [
        'familia' => 'choferes',
        'icono' => 'o-user-circle',
        'orden' => 30,
      ],
      [
        'familia' => 'unidades',
        'icono' => 'o-truck',
        'orden' => 40,
      ],
      [
        'familia' => 'localidades',
        'icono' => 'o-map-pin',
        'orden' => 50,
      ],
      [
        'familia' => 'propuestas',
        'icono' => 'o-document-check',
        'orden' => 60,
      ],
    ];

    foreach ($permisos as $p) {
      foreach (['index', 'show', 'create', 'edit', 'delete'] as $action) {
        Permission::updateOrCreate([
          'name'      => $action . ' ' . $p['familia']
        ]);
      }

      $pid = Permission::where("name", "index $p[familia]")->first()->id;
      AsideItem::updateOrCreate([
        'nombre'        => strtoupper($p['familia']),
      ], [
        'icono'         => $p['icono'],
        'ruta'          => $p['ruta'] ?? 'construccion',
        'permission_id' => $pid,
        'orden'         => $p['orden'],
      ]);
    }

    Role::updateOrCreate(['name' => 'Super Admin']);
    $user->assignRole('Super Admin');
  }

}
