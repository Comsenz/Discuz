<?php


use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateCreditScoreRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('credit_score_rule', function (Blueprint $table) {
            $table->id()->comment('规则 id');
            $table->string('rule', 50)->default('')->comment('规则名');
            $table->string('action', 100)->default('')->unique()->comment('触发行为');
            $table->tinyInteger('cycle_type')->default(0)->comment('周期类型，0：不限，1：一次，2：每天，3：整点，间隔小时，4：整点，间隔分数');
            $table->unsignedInteger('interval_time')->default(0)->comment('时间间隔，单位：秒，cycle_type 为 3或4时候有值');
            $table->unsignedInteger('reward_num')->default(0)->comment('奖励次数，cycle_type=0|1时，奖励次数为0');
            $table->unsignedInteger('score')->default(0)->comment('当前规则奖励的积分数量');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('credit_score_rule');
    }
}
