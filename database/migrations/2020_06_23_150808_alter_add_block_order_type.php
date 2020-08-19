<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAddBlockOrderType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedInteger('post_id')->nullable()->after('type')->comment('post id');
            $table->unsignedInteger('block_payid')->nullable()->after('thread_id')->comment('付费块id');
            $table->unsignedInteger('type')->unsigned()->default(0)->comment('交易类型：1注册、2打赏、3付费主题、4付费用户组、5块付费')->change();
        });

        $this->schema()->table('posts', function (Blueprint $table) {
            $table->json('content')->nullable()->comment('内容')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->dropColumn('post_id');
            $table->dropColumn('block_payid');
        });
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->text('content')->nullable()->comment('内容')->change();
        });
    }
}
