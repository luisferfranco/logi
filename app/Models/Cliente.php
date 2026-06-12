<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use App\Enum\EstadoCliente;

#[Guarded([])]
class Cliente extends Model
{
  /** @use HasFactory<\Database\Factories\ClienteFactory> */
  use HasFactory;

  public function casts(): array
  {
    return [
      'estado' => EstadoCliente::class,
    ];
  }

  public function administrador()
  {
    return $this->belongsTo(User::class);
  }
}
