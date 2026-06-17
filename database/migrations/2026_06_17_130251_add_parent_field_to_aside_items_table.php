<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('aside_items', function (Blueprint $table) {
      $table->dropForeign(['permission_id']);
      $table->dropColumn('permission_id');

      $table->string('permission_name')
        ->nullable();
      $table->foreignId('parent_id')
        ->nullable()
        ->constrained('aside_items')
        ->cascadeOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('aside_items', function (Blueprint $table) {
      $table->dropForeign(['parent_id']);
      $table->dropColumn('parent_id');

      $table->dropColumn('permission_name');

      $table->foreignId('permission_id')
        ->nullable()
        ->constrained('permissions')
        ->nullOnDelete();
    });
  }
};
