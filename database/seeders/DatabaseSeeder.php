<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AsidePermission;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application"s database.
   */
  public function run(): void
  {
    $user = User::updateOrCreate(
      [
        "email" => "lffrancoj@fertinal.com"
      ],
      [
      // nombre
      // email
      // empleado
      // rfc
      // estado
      // email_verified_at
      // password
      // avatar
      // id_externo
      // auth_externo

      "nombre"    => "Luis Fernando Franco",
      "email"     => "lffrancoj@fertinal.com",
      "empleado"  => "10000341",
      "rfc"       => "FAJL710523QP0",
      "estado"    => "activo",
    ]);

    $permisos = [
      [
        'rol'         => 'su',
        'permiso'     => 'gestionar permisos',
        'ruta'        => '#',
        'icono'       => 'o-shield-exclamation',
        'nombre'      => 'Permisos',
        'descripcion' => 'Gestionar permisos del sistema',
        'orden'       => 5,
      ],
      [
        'rol'         => 'admin',
        'permiso'     => 'gestionar usuarios',
        'ruta'        => 'admin.users.index',
        'icono'       => 'o-users',
        'nombre'      => 'Usuarios',
        'descripcion' => 'Gestionar usuarios del sistema',
        'orden'       => 10,
      ],
      [
        'rol'         => 'admin',
        'permiso'     => 'gestionar roles',
        'ruta'        => '#',
        'icono'       => 'o-user-circle',
        'nombre'      => 'Roles',
        'descripcion' => 'Gestionar roles del sistema',
        'orden'       => 20,
      ],
      [
        'rol'         => 'admin',
        'permiso'     => 'gestionar clientes',
        'ruta'        => 'admin.clientes.index',
        'icono'       => 'o-truck',
        'nombre'      => 'Clientes',
        'descripcion' => 'Gestionar clientes del sistema',
        'orden'       => 30,
      ],
      [
        'rol'         => 'transportista',
        'permiso'     => 'gestionar choferes',
        'ruta'        => '#',
        'icono'       => 'o-identification',
        'nombre'      => 'Choferes',
        'descripcion' => 'Gestionar choferes del sistema',
        'orden'       => 40,
      ],
      [
        'rol'         => 'transportista',
        'permiso'     => 'gestionar unidades',
        'ruta'        => '#',
        'icono'       => 'o-truck',
        'nombre'      => 'Unidades',
        'descripcion' => 'Gestionar unidades del transportista',
        'orden'       => 50,
      ],
      [
        'rol'         => 'transportista',
        'permiso'     => 'gestionar localidades',
        'ruta'        => '#',
        'icono'       => 'o-map-pin',
        'nombre'      => 'Localidades',
        'descripcion' => 'Gestionar localidades del transportista',
        'orden'       => 60,
      ],
      [
        'rol'         => 'transportista',
        'permiso'     => 'aceptar propuestas',
        'ruta'        => '#',
        'icono'       => 'o-check-circle',
        'nombre'      => 'Propuestas',
        'descripcion' => 'Aceptar propuestas al transportista',
        'orden'       => 70,
      ],
    ];
    $rol = null;
    foreach ($permisos as $p) {
      if ($p['rol'] !== $rol) {
        $r = Role::updateOrCreate(['name' => $p['rol']]);
        $rol = $p['rol'];
      }
      $per = Permission::updateOrCreate(['name' => $p['permiso']]);
      $r->givePermissionTo($per);
      AsidePermission::updateOrCreate(
        [
          'permission_id' => $per->id,
        ],
        [
          'ruta'        => $p['ruta'],
          'icono'       => $p['icono'],
          'nombre'      => $p['nombre'],
          'descripcion' => $p['descripcion'],
          'orden'       => $p['orden'],
        ]
      );
    }

    $user->assignRole('admin');
    $user->assignRole('su');

    // $perm = [
    //   'gestionar usuarios',
    //   'gestionar clientes',
    //   'gestionar roles',
    // ];
    // $r = Role::updateOrCreate(['name' => 'admin']);
    // foreach ($perm as $p) {
    //   $per = Permission::updateOrCreate(['name' => $p]);
    //   $r->givePermissionTo($per);
    // }
    // $user->assignRole('admin');

    // $perm = [
    //   'gestionar permisos',
    // ]
    // $r = Role::updateOrCreate(['name' => 'su']);
    // foreach ($perm as $p) {
    //   $per = Permission::updateOrCreate(['name' => $p]);
    //   $r->givePermissionTo($per);
    // }
    // $user->assignRole('su');

    // $perm = [
    //   'gestionar choferes',
    //   'gestionar unidades',
    //   'gestionar localidades',
    //   'aceptar propuestas'
    // ];
    // $r = Role::updateOrCreate(['name' => 'transportista']);
    // foreach ($perm as $p) {
    //   $per = Permission::updateOrCreate(['name' => $p]);
    //   $r->givePermissionTo($per);
    // }
  }
}
