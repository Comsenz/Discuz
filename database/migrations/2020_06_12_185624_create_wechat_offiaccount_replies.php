<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWechatOffiaccountReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('wechat_offiaccount_replies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->default('')->comment('规则名');
            $table->string('keyword')->default('')->comment('关键词');
            $table->unsignedTinyInteger('match_type')->default(0)->comment('匹配类型:0全匹配1半匹配');
            $table->unsignedTinyInteger('reply_type')->default(0)->comment('消息回复类型:1文本2图片3语音4视频5音乐6图文');
            $table->string('media_id')->default('')->comment('素材ID');
            $table->unsignedTinyInteger('media_type')->default(0)->comment('素材类型:1图片2视频3语音4图文');
            $table->unsignedTinyInteger('type')->comment('数据类型:0自动回复1消息回复2关键词回复');
            $table->tinyInteger('status')->default(1)->comment('是否开启:0关闭1开启');

            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');

            $table->index('keyword', 'idx_keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('wechat_offiaccount_replies');
    }
}
