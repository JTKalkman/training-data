<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_sport_type_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('data_source_id')->constrained()->cascadeOnDelete();
            $table->string('external_id')->nullable();
            $table->string('external_name');
            $table->string('external_label')->nullable(); // For debugging/admin display only, never shown to end users.
            $table->timestamps();
            
            $table->unique(['data_source_id', 'external_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_sport_type_mappings');
    }
};
