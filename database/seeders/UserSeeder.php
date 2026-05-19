<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super User',
            'email' => 'su@test.com',
            'status' => 'activo',
        ]);
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'status' => 'activo',
        ]);
    }
}
