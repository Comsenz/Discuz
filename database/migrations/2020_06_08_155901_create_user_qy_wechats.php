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

class CreateUserQyWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_qy_wechats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index('user_id')->default(0)->comment('用户 id');
            $table->string('qy_userid', 50)->default('')->comment('企业微信企业用户id, corpid_userid联合全局唯一');
            $table->string('nickname', 50)->default('')->comment('企业微信昵称');
            $table->tinyInteger('sex')->default(0)->comment('性别');
            $table->string('email', 50)->default('')->comment('邮箱');
            $table->string('mobile', 20)->default('')->comment('电话');
            $table->string('address', 50)->default('')->comment('地址');
            $table->string('headimgurl')->default('')->comment('头像');
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
        $this->schema()->dropIfExists('user_qy_wechats');
    }
}
