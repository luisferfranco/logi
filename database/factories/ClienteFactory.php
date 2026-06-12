<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'nombre'    => $this->faker->company(),
      'rfc'       => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{6}[A-Z0-9]{3}'),
      'direccion' => $this->faker->address(),
      'telefono'  => $this->faker->phoneNumber(),
      'estado'    => $this->faker->randomElement(['pendiente', 'activo', 'inactivo']),
      //
    ];
  }
}
