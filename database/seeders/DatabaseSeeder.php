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
    Role::updateOrCreate(['name' => 'Super Admin']);
    $user->assignRole('Super Admin');

    $permisos = [
      'usuarios',
      'viajes',
      'catalogos',
      'roles',
    ];

    foreach ($permisos as $p) {
      foreach (['index', 'show', 'create', 'edit', 'delete'] as $action) {
        Permission::updateOrCreate([
          'name'      => $action . ' ' . $p,
          'guard_name' => 'web',
          ]);
        }
      }
      $this->call([
        AsideSeeder::class,
      ]);
  }

}
