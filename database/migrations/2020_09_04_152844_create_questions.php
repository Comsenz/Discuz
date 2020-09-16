<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('thread_id')->nullable()->comment('主题 id');
            $table->unsignedBigInteger('user_id')->nullable()->comment('提问人用户 id');
            $table->unsignedBigInteger('be_user_id')->nullable()->comment('被提问的用户 id');
            $table->text('content')->nullable()->comment('回答内容');
            $table->ipAddress('ip')->default('')->comment('回答人 ip 地址');
            $table->unsignedInteger('port')->default(0)->comment('回答人端口');
            $table->decimal('price')->unsigned()->default(0)->comment('问答价格');
            $table->decimal('onlooker_unit_price')->unsigned()->default(0)->comment('围观单价');
            $table->decimal('onlooker_price')->unsigned()->default(0)->comment('当前围观总价格');
            $table->unsignedBigInteger('onlooker_number')->default(0)->comment('当前围观总人数');
            $table->tinyInteger('is_onlooker')->default(1)->comment('是否允许围观');
            $table->tinyInteger('is_answer')->default(0)->comment('是否已回答 0未回答 1已回答 2已过期');
            $table->tinyInteger('is_anonymous')->default(0)->comment('是否匿名 0否 1是');
            $table->tinyInteger('is_approved')->unsigned()->default(1)->comment('回答内容是否合法');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('expired_at')->comment('过期时间');

            // 索引
            $table->index('thread_id', 'idx_thread_id');
            $table->index('user_id', 'idx_user_id');
            $table->index('be_user_id', 'idx_be_user_id');

            // 外键
            $table->foreign('thread_id')->references('id')->on('threads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('questions');
    }
}
