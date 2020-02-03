<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmoji extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('emoji', function (Blueprint $table) {
            $table->increments('id')->comment('表情 id');
            $table->string('category')->comment('表情分类');
            $table->string('url')->comment('表情地址');
            $table->string('code')->comment('表情符号');
            $table->unsignedSmallInteger('order')->default(0)->comment('显示顺序');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('emoji');
    }
}
