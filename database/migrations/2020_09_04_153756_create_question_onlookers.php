<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionOnlookers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('question_onlookers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('question_id')->nullable()->comment('问答 id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('围观人用户 id');
            $table->unsignedBigInteger('order_id')->nullable()->comment('订单 id');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('created_at')->comment('创建时间');

            // 索引
            $table->index('question_id', 'idx_question_id');
            $table->index('user_id', 'idx_user_id');

            // 外键
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('question_onlookers');
    }
}
