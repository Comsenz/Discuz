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
            $table->increments('id')->comment('关系 ID');
            $table->unsignedInteger('from_user_id')->index('from_user_id')->comment('关系主体');
            $table->unsignedInteger('to_user_id')->index('to_user_id')->comment('关系客体');
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
        $this->schema()->dropIfExists('user_follow');
    }
}
