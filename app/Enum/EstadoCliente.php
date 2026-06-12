<?php

namespace App\Enum;

enum EstadoCliente: string
{
  case PENDIENTE = 'pendiente';
  case ACTIVO = 'activo';
  case INACTIVO = 'inactivo';

  public function label(): string
  {
    return match ($this) {
      self::PENDIENTE => 'PENDIENTE',
      self::ACTIVO => 'ACTIVO',
      self::INACTIVO => 'INACTIVO',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::PENDIENTE => 'warning',
      self::ACTIVO => 'success',
      self::INACTIVO => 'error',
    };
  }

  public function options(): array
  {
    return array_map(
      fn (self $case) => ['id' => $case->value, 'name' => $case->label()],
      self::cases()
    );
  }
}
