<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIndexToDialogMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->index('dialog_id', 'idx_dialog_id');
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
            //
        });
    }
}
