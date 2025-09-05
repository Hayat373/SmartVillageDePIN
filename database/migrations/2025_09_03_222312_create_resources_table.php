<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceContributionsTable extends Migration
{
    public function up()
    {
        Schema::create('resource_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('resource_type', ['energy', 'bandwidth', 'water', 'storage']);
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resource_contributions');
    }
}