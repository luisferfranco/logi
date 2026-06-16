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
    Schema::create('aside_items', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(Permission::class)->constrained()->onDelete('cascade');
      $table->string('nombre');
      $table->string('descripcion')->nullable();
      $table->string('icono')->default("cube");
      $table->string('ruta')->default("#");
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('aside_items');
  }
};
