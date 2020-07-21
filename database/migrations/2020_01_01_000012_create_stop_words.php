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
