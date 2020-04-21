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
            $table->dateTime('read_at')->nullable()->after('recipient_user_id')->comment('阅读时间');
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
            $table->dropColumn('read_at');
        });
    }
}
