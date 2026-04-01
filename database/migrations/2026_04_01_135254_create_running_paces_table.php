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
        Schema::create('running_pace_zones', function (Blueprint $table) {
            $table->id();
            $table->integer('zone_number');
            $table->string('name')->nullable();
            $table->integer('min_seconds');
            $table->integer('max_seconds');
            $table->string('color', 20)->nullable();
            $table->integer('in_zone_seconds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('running_paces');
    }
};
