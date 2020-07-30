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

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('reports', function (Blueprint $table) {
            $table->id()->comment('举报 id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('用户 id');
            $table->unsignedBigInteger('thread_id')->default(0)->comment('主题 id');
            $table->unsignedBigInteger('post_id')->default(0)->comment('回复 id');
            $table->unsignedTinyInteger('type')->default(0)->comment('举报类型:0个人主页 1主题 2评论/回复');
            $table->text('reason')->comment('举报理由');
            $table->unsignedTinyInteger('status')->default(0)->comment('举报状态:0未处理 1已处理');

            $table->timestamps();

            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('reports');
    }
}
