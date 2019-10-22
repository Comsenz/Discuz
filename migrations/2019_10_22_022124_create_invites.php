<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id')->comment('自增ID');
            $table->unsignedInteger('user_group_id')->comment('默认用户组ID');
            $table->char('code', 32)->default('')->comment('邀请码');
            $table->timestamp('dateline')->default(0)->comment('邀请码生效时间');
            $table->timestamp('endtime')->default(0)->comment('邀请码结束时间');
            $table->unsignedInteger('user_id')->comment('邀请用户ID');
            $table->unsignedInteger('to_user_id')->default(0)->comment('被邀请用户ID');
            $table->unsignedTinyInteger('status')->default(0)->comment('邀请码状态');
            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invites');
    }
}
