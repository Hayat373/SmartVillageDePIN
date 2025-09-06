<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('resource_contributions', function (Blueprint $table) {
            if (!Schema::hasColumn('resource_contributions', 'demand_prediction')) {
                $table->string('demand_prediction')->nullable()->comment('AI prediction: high, medium, low')->after('transaction_id');
            }
            if (!Schema::hasColumn('resource_contributions', 'allocation_recommendation')) {
                $table->text('allocation_recommendation')->nullable()->after('demand_prediction');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('resource_contributions', function (Blueprint $table) {
            $table->dropColumn(['demand_prediction', 'allocation_recommendation']);
        });
    }
};
