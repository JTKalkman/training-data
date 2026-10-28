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
        Schema::create('physical_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('resting_heart_rate')->nullable();
            $table->unsignedSmallInteger('max_heart_rate')->nullable();
            $table->unsignedSmallInteger('aerobic_threshold_bpm')->nullable();
            $table->unsignedSmallInteger('anaerobic_threshold_bpm')->nullable();
            $table->unsignedSmallInteger('mas_seconds_per_km')->nullable();
            $table->unsignedSmallInteger('vo2_max')->nullable();
            $table->unsignedSmallInteger('map_watts')->nullable();
            $table->unsignedSmallInteger('ftp_watts')->nullable();
            $table->timestamps();

            $table->unique(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_data');
    }
};
