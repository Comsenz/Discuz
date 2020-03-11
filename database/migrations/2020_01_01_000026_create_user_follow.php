<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserFollow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_follow', function (Blueprint $table) {
            $table->id()->comment('自增ID');
            $table->unsignedInteger('from_user_id')->index('from_user_id')->comment('关注人');
            $table->unsignedInteger('to_user_id')->index('to_user_id')->comment('被关注人');
            $table->tinyInteger('is_mutual')->default(0)->comment('是否互相关注：0否 1是');
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
        $this->schema()->dropIfExists('user_follow');
    }
}
