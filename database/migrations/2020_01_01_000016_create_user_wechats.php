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

class CreateUserWechats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('user_wechats', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index('user_id')->default(0)->comment('用户 id');
            $table->string('mp_openid', 30)->default('')->comment('公众号openid');
            $table->string('dev_openid', 30)->default('')->comment('开放平台openid');
            $table->string('min_openid', 30)->default('')->comment('小程序openid');
            $table->string('nickname', 20)->default('')->comment('微信昵称');
            $table->tinyInteger('sex')->default(0)->comment('性别');
            $table->string('province', 10)->default('')->comment('省份');
            $table->string('city', 10)->default('')->comment('城市');
            $table->string('country', 10)->default('')->comment('国家');
            $table->string('headimgurl')->default('')->comment('头像');
            $table->string('privilege', 20)->default('')->comment('用户特权信息');
            $table->string('unionid', 30)->default('')->comment('只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段');
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
        $this->schema()->dropIfExists('user_wechats');
    }
}
