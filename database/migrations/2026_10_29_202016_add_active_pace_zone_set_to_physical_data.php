<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('physical_data', function (Blueprint $table) {
            $table->foreignId('active_pace_zone_set_id')
                ->nullable()
                ->constrained('pace_zone_sets')
                ->nullOnDelete();
        });
    }
 
    public function down(): void
    {
        Schema::table('physical_data', function (Blueprint $table) {
            $table->dropConstrainedForeignId('active_pace_zone_set_id');
        });
    }
};
