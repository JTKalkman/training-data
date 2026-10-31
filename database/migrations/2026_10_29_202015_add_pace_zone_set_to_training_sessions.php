<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->foreignId('pace_zone_set_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->boolean('pace_zone_set_is_estimated')
                ->default(false);
        });
    }
 
    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pace_zone_set_id');
            $table->dropColumn('pace_zone_set_is_estimated');
        });
    }
};
