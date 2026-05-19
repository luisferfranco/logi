<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['su', 'admin', 'fertinal', 'transportista'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
        $user = User::find(1);
        $user->assignRole('su');

        $user = User::find(2);
        $user->assignRole('admin');
    }
}
