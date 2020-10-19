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
            $table->decimal('attachment_price')->unsigned()->default(0)->after('price')->comment('附件价格');
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
