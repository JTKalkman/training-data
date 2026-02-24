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
        Schema::create('training_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('training_session_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->integer('min_heart_rate')->nullable();
            $table->integer('avg_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_summaries');
    }
};
