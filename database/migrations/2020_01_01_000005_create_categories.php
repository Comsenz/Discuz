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

class CreateCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->create('categories', function (Blueprint $table) {
            $table->id()->comment('分类 id');
            $table->string('name')->default('')->comment('分类名称');
            $table->text('description')->comment('分类描述');
            $table->string('icon')->default('')->comment('分类图标');
            $table->unsignedSmallInteger('sort')->default(0)->comment('显示顺序');
            $table->unsignedTinyInteger('property')->default(0)->comment('属性：0 正常 1 首页展示');
            $table->unsignedInteger('thread_count')->default(0)->comment('主题数');
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
        $this->schema()->dropIfExists('categories');
    }
}
