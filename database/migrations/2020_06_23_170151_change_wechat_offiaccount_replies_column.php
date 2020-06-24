<?php

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
            $tableName = $table->getTable();

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
