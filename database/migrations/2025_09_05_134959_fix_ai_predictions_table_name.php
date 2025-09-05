<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAiPredictionsTableName extends Migration
{
    public function up()
    {
        // Check if the old table name exists
        if (Schema::hasTable('ai_predictions')) {
            // Rename the table to what Eloquent expects
            DB::statement('ALTER TABLE ai_predictions RENAME TO a_i_predictions');
        }
    }

    public function down()
    {
        if (Schema::hasTable('a_i_predictions')) {
            DB::statement('ALTER TABLE a_i_predictions RENAME TO ai_predictions');
        }
    }
}