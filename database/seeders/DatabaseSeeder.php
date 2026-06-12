<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application"s database.
   */
  public function run(): void
  {
    if (!$user = User::where("email", "lffrancoj@fertinal.com")->first()) {
      $user = User::factory()->create(
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
    }

    $perm = [
      'gestionar usuarios',
      'gestionar clientes',
    ];
    $r = Role::updateOrCreate(['name' => 'admin']);
    foreach ($perm as $p) {
      $per = Permission::updateOrCreate(['name' => $p]);
      $r->givePermissionTo($per);
    }
    $user->assignRole('admin');

    $perm = [
      'gestionar choferes',
      'gestionar unidades',
      'gestionar localidades',
      'aceptar propuestas'
    ];
    $r = Role::updateOrCreate(['name' => 'transportista']);
    foreach ($perm as $p) {
      $per = Permission::updateOrCreate(['name' => $p]);
      $r->givePermissionTo($per);
    }
  }
}
