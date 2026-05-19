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
      $table->string('avatar')->nullable()->after('email');
      $table->string('invitation_code')->nullable()->after('avatar');
      $table->timestamp('invitation_expires')->nullable()->after('invitation_code');
      $table->enum('status', ['invitado', 'activo', 'suspendido'])
        ->default('invitado')
        ->after('invitation_expires');
      $table->boolean('tos_accepted')->default(false)->after('status');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn(['avatar', 'invitation_code', 'invitation_expires', 'status', 'tos_accepted']);
    });
  }
};
