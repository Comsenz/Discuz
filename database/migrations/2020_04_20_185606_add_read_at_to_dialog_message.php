<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddReadAtToDialogMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->dateTime('read_at')->nullable()->after('message_text')->comment('阅读时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->dropColumn('read_at');
        });
    }
}
