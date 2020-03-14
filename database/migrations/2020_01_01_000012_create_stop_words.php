<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStopWords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('stop_words', function (Blueprint $table) {
            $table->id()->comment('敏感词 id');
            $table->integer('user_id')->unsigned()->nullable()->comment('创建用户 id');
            $table->string('ugc', 10)->default('')->comment('用户内容处理方式');
            $table->string('username', 10)->default('')->comment('用户名处理方式');
            $table->string('find')->default('')->comment('敏感词或查找方式');
            $table->string('replacement')->default('')->comment('替换词或替换规则');
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
        $this->schema()->dropIfExists('stop_words');
    }
}
