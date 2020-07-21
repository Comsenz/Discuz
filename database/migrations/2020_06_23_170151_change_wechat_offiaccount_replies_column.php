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

class ChangeWechatOffiaccountRepliesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('wechat_offiaccount_replies', function (Blueprint $table) use (&$tableName) {
            // 获取数据库名
            $tableName = config('database.connections.mysql.prefix') . $table->getTable();

            $table->string('content')->default('')->after('reply_type')->comment('回复文本内容');
        });

        $sql = "alter table {$tableName} change `reply_type` `reply_type` tinyint(3) unsigned not null default 1 comment '消息回复类型'";
        $sql2 = "alter table {$tableName} change `type` `type` tinyint(3) unsigned not null default 2 comment '数据类型:0被关注回复1消息回复2关键词回复'";
        $this->schema()->getConnection()->statement($sql);
        $this->schema()->getConnection()->statement($sql2);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('wechat_offiaccount_replies', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
}
