<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixAllTables extends Migration
{
    public function up()
    {
        // Add columns to users table if they don't exist
        if (!Schema::hasColumn('users', 'hedera_account_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('hedera_account_id')->nullable()->after('password');
            });
        }
        
        if (!Schema::hasColumn('users', 'village_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('village_name')->nullable()->after('hedera_account_id');
            });
        }
        
        if (!Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('country')->nullable()->after('village_name');
            });
        }
        
        if (!Schema::hasColumn('users', 'reputation_score')) {
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('reputation_score', 5, 2)->default(0)->after('country');
            });
        }
        
        if (!Schema::hasColumn('users', 'is_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_verified')->default(false)->after('reputation_score');
            });
        }

        // Create resource_contributions table if it doesn't exist
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

        // Create ai_predictions table if it doesn't exist
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

        // Create village_stats table if it doesn't exist
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

        // Create resource_allocations table if it doesn't exist
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
        // We'll leave this empty to avoid accidentally dropping tables
        // during development
    }
}