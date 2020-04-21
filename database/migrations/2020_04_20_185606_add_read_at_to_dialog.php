<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReadAtToDialog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog', function (Blueprint $table) {
            $table->dateTime('sender_read_at')->nullable()->after('recipient_user_id')->comment('发送人阅读时间');
            $table->dateTime('recipient_read_at')->nullable()->after('recipient_user_id')->comment('收信人阅读时间');
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
            $table->dropColumn('sender_read_at');
            $table->dropColumn('recipient_read_at');
        });
    }
}
