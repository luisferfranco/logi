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
    Schema::table('users', function (Blueprint $table) {
      $table->string('token_recuperacion')
        ->nullable()
        ->after('remember_token');
      $table->timestamp('token_recuperacion_expira')
        ->nullable()
        ->after('token_recuperacion');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn('token_recuperacion');
      $table->dropColumn('token_recuperacion_expira');
    });
  }
};
