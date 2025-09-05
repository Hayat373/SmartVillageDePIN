<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceContributionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('resource_contributions')) {
            Schema::create('resource_contributions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('resource_type', ['energy', 'bandwidth', 'water', 'storage', 'computing']);
                $table->decimal('amount', 15, 6);
                $table->string('transaction_id')->nullable();
                $table->string('status')->default('pending');
                $table->text('ai_analysis')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'resource_type']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('resource_contributions');
    }
}