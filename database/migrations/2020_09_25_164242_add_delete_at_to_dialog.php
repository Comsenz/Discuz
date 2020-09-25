<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDeleteAtToDialog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog', function (Blueprint $table) {
            $table->dateTime('sender_deleted_at')->nullable()->comment('发送人删除时间');
            $table->dateTime('recipient_deleted_at')->nullable()->comment('收信人删除时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('dialog', function (Blueprint $table) {
            $table->dropColumn('sender_deleted_at');
            $table->dropColumn('recipient_deleted_at');
        });
    }
}
