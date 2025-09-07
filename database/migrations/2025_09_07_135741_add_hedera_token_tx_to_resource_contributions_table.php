<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHederaTokenTxToResourceContributionsTable extends Migration
{
    public function up()
    {
        Schema::table('resource_contributions', function (Blueprint $table) {
            $table->string('hedera_token_tx')->nullable()->after('transaction_id');
        });
    }

    public function down()
    {
        Schema::table('resource_contributions', function (Blueprint $table) {
            $table->dropColumn('hedera_token_tx');
        });
    }
}