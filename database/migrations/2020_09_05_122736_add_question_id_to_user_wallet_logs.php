<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddQuestionIdToUserWalletLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wallet_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('question_id')->default(0)->after('user_wallet_cash_id')->comment('关联问答记录 id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_wallet_logs', function (Blueprint $table) {
            $table->dropColumn('question_id');
        });
    }
}
