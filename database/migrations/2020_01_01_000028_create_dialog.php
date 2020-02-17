<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDialog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('dialog', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('自增ID');
            $table->unsignedBigInteger('dialog_message_id')->default(0)->comment('最新消息ID');
            $table->unsignedBigInteger('sender_user_id')->index('sender_user_id')->comment('发送人UID');
            $table->unsignedBigInteger('recipient_user_id')->index('recipient_user_id')->comment('收信人UID');
            $table->dateTime('updated_at')->comment('更新时间');
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
        $this->schema()->dropIfExists('dialog');
    }
}
