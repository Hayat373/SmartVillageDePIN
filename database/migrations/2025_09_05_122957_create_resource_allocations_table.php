<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceAllocationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('resource_allocations')) {
            Schema::create('resource_allocations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('contribution_id')->constrained('resource_contributions')->onDelete('cascade');
                $table->string('recipient_village');
                $table->decimal('allocated_amount', 15, 6);
                $table->string('allocation_strategy');
                $table->string('transaction_id')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('resource_allocations');
    }
}