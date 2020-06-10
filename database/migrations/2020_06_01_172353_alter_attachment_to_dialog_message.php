<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterAttachmentToDialogMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->unsignedInteger('attachment_id')->after('user_id')->comment('附件ID');
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
            $table->dropColumn('attachment_id');
        });
    }
}
