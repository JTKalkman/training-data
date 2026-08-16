<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('polar_profiles', function (Blueprint $table) {
            $table->timestamp('next_sync_at')->nullable()->index();
            $table->timestamp('locked_at')->nullable();
        });

        // Existing profiles: make them immediately eligible for the new scheduler,
        // but skip anyone already unlinked, they should stay excluded.
        DB::table('polar_profiles')
            ->whereNull('unlinked_at')
            ->update(['next_sync_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polar_profiles', function (Blueprint $table) {
            $table->dropColumn('next_sync_at', 'locked_at');
        });
    }
};
