<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('topics', function (Blueprint $table) {
            $table->id()->comment('话题ID');
            $table->unsignedBigInteger('user_id')->nullable()->comment('user_id');
            $table->text('content')->nullable()->comment('话题名称');
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
        $this->schema()->dropIfExists('topics');
    }
}
