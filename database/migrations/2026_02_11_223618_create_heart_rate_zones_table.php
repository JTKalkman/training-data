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
        Schema::create('heart_rate_zones', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('training_session_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('zone_number');
            $table->string('name')->nullable();
            $table->integer('min_bpm');
            $table->integer('max_bpm');
            $table->string('color', 20)->nullable();
            $table->timestamps();

            $table->index(['training_session_id', 'zone_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heart_rate_zones');
    }
};
