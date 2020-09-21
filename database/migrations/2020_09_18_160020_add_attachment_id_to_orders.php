<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddAttachmentIdToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedInteger('attachment_id')->nullable()->after('group_id')->comment('附件ID');
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
            $table->dropColumn('attachment_id');
        });
    }
}
