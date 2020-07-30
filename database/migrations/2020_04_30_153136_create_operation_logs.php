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

class CreateOperationLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('operation_logs', function (Blueprint $table) {
            $table->id()->comment('日志 id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('用户 id');
            $table->string('path')->default('')->comment('url路径');
            $table->string('method', 10)->default('')->comment('请求方式');
            $table->ipAddress('ip')->default('')->comment('ip 地址');
            $table->text('input')->comment('body请求数据');
            $table->unsignedTinyInteger('type')->default(0)->comment('日志类型:0后台操作');

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
        $this->schema()->dropIfExists('operation_logs');
    }
}
