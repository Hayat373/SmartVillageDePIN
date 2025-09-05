<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVillageStatsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('village_stats')) {
            Schema::create('village_stats', function (Blueprint $table) {
                $table->id();
                $table->string('village_name');
                $table->string('resource_type');
                $table->decimal('total_contributed', 15, 6)->default(0);
                $table->decimal('total_allocated', 15, 6)->default(0);
                $table->integer('contributor_count')->default(0);
                $table->date('stat_date');
                $table->timestamps();
                $table->unique(['village_name', 'resource_type', 'stat_date']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('village_stats');
    }
}