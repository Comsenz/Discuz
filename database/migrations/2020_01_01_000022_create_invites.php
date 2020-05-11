<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('invites', function (Blueprint $table) {
            $table->id()->comment('邀请 id');
            $table->unsignedInteger('group_id')->comment('默认用户组 id');
            $table->unsignedTinyInteger('type')->default(1)->comment('类型:1普通用户2管理员');
            $table->char('code', 32)->default('')->comment('邀请码');
            $table->unsignedInteger('dateline')->default('0')->comment('邀请码生效时间');
            $table->unsignedInteger('endtime')->default('0')->comment('邀请码结束时间');
            $table->unsignedInteger('user_id')->comment('邀请用户 id');
            $table->unsignedInteger('to_user_id')->default(0)->comment('被邀请用户 id');
            $table->unsignedTinyInteger('status')->default(1)->comment('邀请码状态:0失效1未使用2已使用3已过期');
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
        $this->schema()->dropIfExists('invites');
    }
}
