<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;

#[Guarded([])]
class AsideItem extends Model
{
  public function permiso() {
    return $this->hasOne(Permiso::class);
  }
}
