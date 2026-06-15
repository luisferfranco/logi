<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Guarded([])]
class AsidePermission extends Model
{
  public function permission(): HasOne
  {
    return $this->hasOne(Permission::class);
  }
}
