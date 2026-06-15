<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('aside_permissions', function (Blueprint $table) {
      $table->id();

      $table->foreignIdFor(Permission::class)
        ->constrained()
        ->cascadeOnDelete();
      $table->string('ruta');
      $table->string('icono');
      $table->string('nombre');
      $table->text('descripcion')->nullable();
      $table->integer('orden')->default(0);

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('aside_permissions');
  }
};
