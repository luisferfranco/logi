<?php

namespace App\Enum;

enum UserStatus: string
{
  case ACTIVO = 'activo';
  case INVITADO = 'invitado';
  case SUSPENDIDO = 'suspendido';

  public function label(): string
  {
    return match ($this) {
      self::ACTIVO      => 'Activo',
      self::INVITADO    => 'Invitado',
      self::SUSPENDIDO  => 'Suspendido',
    };
  }

  public function color(): string
  {
    return match ($this) {
      self::ACTIVO      => 'success',
      self::INVITADO    => 'info',
      self::SUSPENDIDO  => 'error',
    };
  }
}
