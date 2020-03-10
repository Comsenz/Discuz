<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationTpls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('notification_tpls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('status')->default(0)->comment('模板状态:1开启0关闭');
            $table->unsignedTinyInteger('type')->default(0)->comment('通知类型:0系统1微信2短信');
            $table->string('type_name')->default('')->comment('类型名称');
            $table->string('title')->default('')->comment('标题');
            $table->text('content')->default('')->comment('内容');
            $table->string('vars')->default('')->comment('可选的变量');
            $table->string('template_id')->default('')->comment('模板ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('notifications_tpl');
    }
}
