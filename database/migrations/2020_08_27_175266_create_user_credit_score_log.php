<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateUserCreditScoreLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_credit_score_log', function (Blueprint $table) {
            $table->id()->comment('自增id');
            $table->unsignedInteger('uid')->default(0)->comment('关联用户id');
            $table->unsignedInteger('rid')->default(0)->comment('关联规则id');
            $table->dateTime('created_at')->comment('创建时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('user_credit_score_log');
    }
}
