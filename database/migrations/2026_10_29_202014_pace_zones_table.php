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
        Schema::create('pace_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pace_zone_set_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('zone_number');
            $table->string('name')->nullable();
            $table->unsignedSmallInteger('min_seconds_per_km');
            $table->unsignedSmallInteger('max_seconds_per_km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pace_zones');
    }
};
