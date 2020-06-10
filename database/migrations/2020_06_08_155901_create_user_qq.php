<?php
/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserQq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_qq', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index('user_id')->default(0)->comment('用户 id');
            $table->string('openid', 50)->default('')->comment('openid');
            $table->string('nickname', 100)->default('')->comment('qq昵称');
            $table->tinyInteger('sex')->default(0)->comment('性别');
            $table->string('province', 10)->default('')->comment('省份');
            $table->string('city', 10)->default('')->comment('城市');
            $table->string('headimgurl')->default('')->comment('头像');
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
        $this->schema()->dropIfExists('user_qq');
    }
}
