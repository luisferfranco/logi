<?php

namespace App\Enum;

enum EstadoUsuario: string
{
  case ACTIVO     = 'activo';
  case PENDIENTE  = 'pendiente';
  case INACTIVO   = 'inactivo';
  case BLOQUEADO  = 'bloqueado';

  public function label(): string
  {
    return match($this) {
      self::ACTIVO    => 'ACTIVO',
      self::PENDIENTE => 'PENDIENTE',
      self::INACTIVO  => 'INACTIVO',
      self::BLOQUEADO => 'BLOQUEADO',
    };
  }

  public function color(): string
  {
    return match($this) {
      self::ACTIVO    => 'success',
      self::PENDIENTE => 'warning',
      self::INACTIVO  => 'neutral',
      self::BLOQUEADO => 'error',
    };
  }
}
