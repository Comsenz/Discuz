<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAttachmentPriceToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->decimal('attachment_price')->unsigned()->default(0)->comment('是否推荐到首页（0否 1是）');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('threads', function (Blueprint $table) {
            $table->dropColumn('attachment_price');
        });
    }
}
