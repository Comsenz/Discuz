<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterTypeToAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->renameColumn('is_gallery', 'type');
            $table->renameColumn('post_id', 'type_id');
            $table->dropColumn('is_sound');
        });

        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->after('user_id')->comment('类型数据ID(post_id,dialog_message_id…)')->change();
            $table->unsignedSmallInteger('type')->after('user_id')->comment('附件类型(0帖子附件，1帖子图片，2帖子视频，3帖子音频，4消息图片)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->renameColumn('type', 'is_gallery');
            $table->renameColumn('type_id', 'post_id');
        });
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->unsignedSmallInteger('is_gallery')->after('order')->comment('是否是帖子图片')->change();
            $table->unsignedInteger('post_id')->after('user_id')->comment('帖子 id')->change();
            $table->unsignedTinyInteger('is_sound')->after('is_approved')->default(0)->comment('是否是音频：0文件1音频2视频');
        });
    }
}
