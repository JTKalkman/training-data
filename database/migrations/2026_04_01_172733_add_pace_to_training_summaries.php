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
        Schema::table('training_summaries', function (Blueprint $table) {
            $table->integer('min_pace_seconds')->nullable();
            $table->integer('avg_pace_seconds')->nullable();
            $table->integer('max_pace_seconds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_summaries', function (Blueprint $table) {
            //
        });
    }
};
