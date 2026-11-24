<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('training_sessions')
            ->whereNotNull('utc_offset')
            ->update([
                'started_at' => DB::raw('DATE_SUB(started_at, INTERVAL utc_offset MINUTE)')
            ]);
    }

    /**
     * Reverse the migrations.
     * 
     * This is only safe when immediately after the up method.
     */
    public function down(): void
    {
        DB::table('training_sessions')
            ->whereNotNull('utc_offset')
            ->update([
                'started_at' => DB::raw('DATE_ADD(started_at, INTERVAL utc_offset MINUTE)')
            ]);
    }
};
