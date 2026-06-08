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
    $user = User::factory()->create([
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

    $r = Role::create(['name' => 'admin']);
    $p = Permission::create(['name' => 'gestionar usuarios']);
    $r->givePermissionTo($p);
    $user->assignRole('admin');

  }
}
