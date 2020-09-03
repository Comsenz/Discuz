<?php



use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateUserCreditScoreStatistics extends  Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_credit_score_statistics', function (Blueprint $table) {
            $table->id()->comment('自增id');
            $table->unsignedInteger('uid')->default(0)->unique()->comment('关联用户id');
            $table->unsignedInteger('sum_score')->default(0)->comment('用户总积分');
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
        $this->schema()->dropIfExists('user_credit_score_statistics');
    }

}
