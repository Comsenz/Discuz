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

class CreateMobileCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('mobile_codes', function (Blueprint $table) {
            $table->id()->comment('验证码 id');
            $table->string('mobile', 20)->default('')->comment('手机号');
            $table->string('code', 10)->default('')->comment('验证码');
            $table->string('type', 20)->default('')->comment('验证类型');
            $table->tinyInteger('state')->default(0)->comment('验证状态');
            $table->ipAddress('ip')->default('')->comment('ip');
            $table->dateTime('expired_at')->nullable()->comment('验证码过期时间');
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
        $this->schema()->dropIfExists('mobile_codes');
    }
}
