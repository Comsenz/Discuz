<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class DialogMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('dialog_message', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('自增ID');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->text('message_text')->nullable()->comment('内容');
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
        $this->schema()->dropIfExists('dialog_message');
    }
}
