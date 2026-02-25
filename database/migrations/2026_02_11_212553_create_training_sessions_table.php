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
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sport_type_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at');
            $table->integer('utc_offset')->nullable();
            $table->integer('duration_seconds');
            $table->foreignId('data_source_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_id');
            $table->timestamps();

            // Unique per user and source to handle edge cases where
            // multiple users share the same device (e.g. shared Polar watch)
            $table->unique(['user_id', 'data_source_id', 'external_id']);

            $table->index('user_id');
            $table->index('sport_type_id');
            $table->index('started_at');
            $table->index(['user_id', 'started_at']);
            $table->index(['user_id', 'sport_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
