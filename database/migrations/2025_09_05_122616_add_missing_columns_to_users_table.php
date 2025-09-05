<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToUsersTable extends Migration
{
    public function up()
    {
        // Check if hedera_account_id already exists before adding it
        if (!Schema::hasColumn('users', 'hedera_account_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('hedera_account_id')->nullable()->after('password');
            });
        }

        // Add other missing columns
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
    }

    public function down()
    {
        // We'll only remove columns if they exist to avoid errors
        if (Schema::hasColumn('users', 'village_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('village_name');
            });
        }

        if (Schema::hasColumn('users', 'country')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('country');
            });
        }

        if (Schema::hasColumn('users', 'reputation_score')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('reputation_score');
            });
        }

        if (Schema::hasColumn('users', 'is_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_verified');
            });
        }
        
        // Note: We won't drop hedera_account_id as it might contain data
    }
}