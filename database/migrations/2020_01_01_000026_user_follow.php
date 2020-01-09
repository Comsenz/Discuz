<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserFollow extends Migration
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
            $table->unsignedInteger('from_uid')->index('from_uid')->comment('关系主体');
            $table->unsignedInteger('to_uid')->index('to_uid')->comment('关系客体');
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
