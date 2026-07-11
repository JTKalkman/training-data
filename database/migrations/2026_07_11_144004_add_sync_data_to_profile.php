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
        Schema::table('polar_profiles', function (Blueprint $table) {
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('last_sync_attempted_at')->nullable();
            $table->text('last_sync_error')->nullable();
            $table->unsignedSmallInteger('consecutive_sync_failures')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polar_profiles', function (Blueprint $table) {
            $table->dropColumn(['last_synced_at', 'last_sync_attempted_at', 'last_sync_error', 'consecutive_sync_failures']);
        });
    }
};
