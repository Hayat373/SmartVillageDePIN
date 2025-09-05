<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiPredictionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ai_predictions')) {
            Schema::create('ai_predictions', function (Blueprint $table) {
                $table->id();
                $table->string('village_name');
                $table->string('resource_type');
                $table->decimal('predicted_demand', 15, 6);
                $table->decimal('predicted_supply', 15, 6);
                $table->decimal('allocation_recommendation', 15, 6);
                $table->date('prediction_date');
                $table->decimal('accuracy_score', 5, 2)->nullable();
                $table->timestamps();
                $table->index(['village_name', 'prediction_date']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('ai_predictions');
    }
}