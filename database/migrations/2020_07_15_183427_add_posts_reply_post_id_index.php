<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPostsReplyPostIdIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->index('reply_post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->dropIndex('reply_post_id');
        });
    }
}
