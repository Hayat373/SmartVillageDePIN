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
             Schema::create('resources', function (Blueprint $table) {
                 $table->id();
                 $table->foreignId('user_id')->constrained();
                 $table->string('type');
                 $table->float('amount');
                 $table->string('hedera_tx_id')->nullable();
                 $table->timestamps();
             });
         }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
