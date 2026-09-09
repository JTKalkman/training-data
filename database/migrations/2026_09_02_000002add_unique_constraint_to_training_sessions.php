<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            // Closes the race window between the API sync and the export
            // import for the same activity: their external_id formats
            // differ (hashed vs raw), so the existing unique constraint
            // on (user_id, data_source_id, external_id) can't catch a
            // duplicate across those two sources. This one does, using
            // started_at as the shared signal instead.
            $table->unique(
                ['user_id', 'data_source_id', 'started_at'],
                'training_sessions_user_data_source_started_at_unique'
            );
        });
    }
 
    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropUnique('training_sessions_user_data_source_started_at_unique');
        });
    }
};
