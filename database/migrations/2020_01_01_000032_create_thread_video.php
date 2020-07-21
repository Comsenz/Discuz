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

class CreateThreadVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('thread_video', function (Blueprint $table) {
            $table->id()->comment('音视频 id');
            $table->integer('thread_id')->unsigned()->index()->comment('主题 id');
            $table->integer('user_id')->unsigned()->comment('用户 id');
            $table->tinyInteger('status')->default(0)->comment('音视频状态：0 转码中 1 转码完成 2 转码失败');
            $table->string('reason')->default('')->comment('转码失败原因');
            $table->string('file_name')->default('')->comment('文件名');
            $table->string('file_id')->default('')->comment('媒体文件唯一标识');
            $table->string('media_url')->default('')->comment('媒体播放地址');
            $table->string('cover_url')->default('')->comment('媒体封面地址');
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
        $this->schema()->dropIfExists('thread_video');
    }
}
