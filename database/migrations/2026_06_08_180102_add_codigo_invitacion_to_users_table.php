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
      $table->string('codigo_invitacion')->nullable()->after('estado');
      $table->datetime('expiracion_invitacion')->nullable()->after('codigo_invitacion');
      $table->datetime('accepted_at')->nullable()->after('expiracion_invitacion');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['codigo_invitacion', 'expiracion_invitacion', 'accepted_at']);
    });
  }
};
