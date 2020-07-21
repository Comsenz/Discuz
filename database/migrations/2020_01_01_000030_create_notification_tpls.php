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

class CreateNotificationTpls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('notification_tpls', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0)->comment('模板状态:1开启0关闭');
            $table->unsignedTinyInteger('type')->default(0)->comment('通知类型:0系统1微信2短信');
            $table->string('type_name')->default('')->comment('类型名称');
            $table->string('title')->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->string('vars')->default('')->comment('可选的变量');
            $table->string('template_id')->default('')->comment('模板ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->dropIfExists('notifications_tpl');
    }
}
