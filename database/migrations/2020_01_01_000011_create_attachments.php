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

class CreateAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('attachments', function (Blueprint $table) {
            $table->id()->comment('附件 id');
            $table->uuid('uuid')->comment('uuid');
            $table->unsignedInteger('user_id')->comment('用户 id');
            $table->unsignedInteger('post_id')->default(0)->comment('帖子 id');
            $table->unsignedTinyInteger('is_gallery')->default(0)->comment('是否是帖子图片');
            $table->unsignedTinyInteger('is_sound')->default(0)->comment('是否是音频：0文件1音频2视频');
            $table->unsignedTinyInteger('is_remote')->default(0)->comment('是否远程附件');
            $table->unsignedTinyInteger('is_approved')->default(1)->comment('是否合法');
            $table->string('attachment')->default('')->comment('文件系统生成的名称');
            $table->string('file_path')->default('')->comment('文件路径');
            $table->string('file_name')->default('')->comment('文件原名称');
            $table->unsignedInteger('file_size')->default(0)->comment('文件大小');
            $table->string('file_type')->default('')->comment('文件类型');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
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
        $this->schema()->dropIfExists('attachments');
    }
}
