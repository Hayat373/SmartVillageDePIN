<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnhancedTables extends Migration
{
    public function up()
    {
        // REMOVE THIS SECTION - these columns are already added by previous migration
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('hedera_account_id')->nullable()->after('password');
        //     $table->string('village_name')->nullable()->after('hedera_account_id');
        //     $table->string('country')->nullable()->after('village_name');
        //     $table->decimal('reputation_score', 5, 2)->default(0)->after('country');
        //     $table->boolean('is_verified')->default(false)->after('reputation_score');
        // });

        // Only create the new tables that don't exist yet
        
        // Resource contributions table (if it doesn't exist)
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

        // AI predictions table
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

        // Resource allocations table
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

        // Village stats table
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
        Schema::dropIfExists('resource_allocations');
        Schema::dropIfExists('ai_predictions');
        Schema::dropIfExists('village_stats');
        Schema::dropIfExists('resource_contributions');
        
        // Don't drop user columns as they might be used by other migrations
    }
}